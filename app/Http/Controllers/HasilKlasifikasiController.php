<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHasilKlasifikasiRequest;
use App\Models\DataHafalan;
use App\Models\HasilKlasifikasi;
use App\Models\ModelSvm;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HasilKlasifikasiController extends Controller
{
    /** URL endpoint Python/SVM service (konfigurasi di .env dengan SVM_SERVICE_URL) */
    private string $svmServiceUrl;

    public function __construct()
    {
        $this->svmServiceUrl = config('services.svm.url', env('SVM_SERVICE_URL', 'http://localhost:5000/predict'));
    }

    /**
     * Admin: semua hasil. Guru: hasil siswa yang diampu. Siswa: hasil miliknya.
     */
    public function index()
    {
        $user = auth()->user();
        $query = HasilKlasifikasi::with(['siswa.user', 'siswa.guru.user', 'modelSvm']);

        if ($user->role === 'guru') {
            // Filter: hanya siswa yang diampu guru ini
            $query->whereHas('siswa', fn($q) => $q->where('id_guru', $user->guru->id));
            $view = 'guru.klasifikasi.index';
        }
        elseif ($user->role === 'siswa') {
            $query->where('id_siswa', $user->siswa->id);
            $view = 'siswa.klasifikasi.index';
        }
        else {
            $view = 'admin.klasifikasi.index';
        }

        $hasilKlasifikasi = $query->latest('tanggal_klasifikasi')->paginate(15);

        // Calculate summary for the view cards
        $ringkasanQuery = clone $query;
        $allResults = $ringkasanQuery->get();
        $ringkasan = [
            'total'       => $allResults->count(),
            'Lulus'       => $allResults->where('kelas_prediksi', 'Lulus')->count(),
            'Tidak Lulus' => $allResults->where('kelas_prediksi', 'Tidak Lulus')->count(),
        ];

        return view($view, compact('hasilKlasifikasi', 'ringkasan'));
    }

    /**
     * Detail hasil klasifikasi beserta vector SVM dan rekomendasi media.
     */
    public function show(HasilKlasifikasi $hasilKlasifikasi)
    {
        $this->authorizeShow($hasilKlasifikasi);

        $hasilKlasifikasi->load([
            'siswa.user',
            'siswa.guru.user',
            'modelSvm',
        ]);

        $view = match (auth()->user()->role) {
                'admin' => 'admin.klasifikasi.show',
                'guru' => 'guru.klasifikasi.show',
                default => 'siswa.klasifikasi.show',
            };
        return view($view, ['hasil' => $hasilKlasifikasi]);
    }

    /**
     * Method utama klasifikasi SVM Semester:
     * 1) Ambil semua hafalan siswa pada semester tersebut.
     * 2) Hitung Total Surat, rata-rata usia, modus media.
     * 3) Kirim ke endpoint Python SVM.
     * 4) Simpan hasil ke tabel hasil_klasifikasi.
     */
    public function klasifikasiSemester(\Illuminate\Http\Request $request)
    {
        abort_if(auth()->user()->role !== 'guru', 403);
        $request->validate([
            'id_siswa' => 'required|exists:tb_siswa,id',
            'periode_semester' => 'required|string|max:50'
        ]);

        $id_siswa = $request->id_siswa;
        $periode = $request->periode_semester;

        try {
            // Ambil semua hafalan di semester ini
            $hafalans = DataHafalan::where('id_siswa', $id_siswa)
                                   ->where('periode_semester', $periode)
                                   ->get();

            if ($hafalans->isEmpty()) {
                return back()->with('error', 'Siswa ini belum memiliki riwayat hafalan pada semester ' . $periode);
            }

            // Hitung Total Surat Unik (berdasarkan nama_surah)
            $totalSurah = $hafalans->unique('nama_surah')->count();

            // Hitung Modus Media
            $modusMedia = $hafalans->groupBy('id_media')->sortDesc()->keys()->first() ?? 1;

            // Ambil Usia
            $siswa = \App\Models\Siswa::find($id_siswa);
            $usia = 12;
            if ($siswa->tanggal_lahir) {
                $usia = date('Y') - $siswa->tanggal_lahir;
            }

            // Hapus hasil klasifikasi lama di semester ini jika ada
            HasilKlasifikasi::where('id_siswa', $id_siswa)
                            ->where('periode_semester', $periode)
                            ->delete();

            // 1. Ambil model SVM aktif
            $model = ModelSvm::where('is_active', true)->firstOrFail();

            // 2. Bangun vector fitur
            $fitur = [
                'total_surah' => $totalSurah,
                'usia' => $usia,
                'id_media' => $modusMedia,
            ];

            // 3. Eksekusi script Python SVM via CLI
            $pythonPath = env('PYTHON_PATH', 'python'); 
            $scriptPath = base_path('svm_service/script_prediksi.py');

            // Susun Argumen sesuai urutan: total_surah, usia, id_media
            $args = [
                $pythonPath,
                $scriptPath,
                $fitur['total_surah'],
                $fitur['usia'],
                $fitur['id_media']
            ];

            $process = new \Symfony\Component\Process\Process($args);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \Exception('Skrip AI gagal dieksekusi: ' . $process->getErrorOutput());
            }

            $output = $process->getOutput();
            $hasil = json_decode(trim($output), true);

            if (!$hasil || !isset($hasil['prediction'])) {
                throw new \Exception('Respons Python bukan JSON yang valid: ' . $output);
            }

            // 4. Simpan hasil klasifikasi semester
            $hasilKlasifikasi = DB::transaction(function () use ($siswa, $periode, $model, $fitur, $hasil, $totalSurah) {
                return HasilKlasifikasi::create([
                    'id_siswa' => $siswa->id,
                    'periode_semester' => $periode,
                    'total_surah' => $totalSurah,
                    'id_model' => $model->id,
                    'kelas_prediksi' => $hasil['prediction'] ?? 'Tidak Lulus',
                    'confidence_score' => $hasil['confidence'] ?? 0.0,
                    'media_input' => json_encode($fitur),
                    'notifikasi_terkirim' => false,
                    'tanggal_klasifikasi' => now(),
                    'vector_svm' => isset($hasil['fitur']) ? $hasil['fitur'] : $fitur,
                ]);
            });

            return back()->with('success', 'Evaluasi Semester ' . $periode . ' berhasil diproses.');
        }
        catch (\Exception $e) {
            Log::error('Klasifikasi SVM Semester gagal: ' . $e->getMessage(), [
                'id_siswa' => $id_siswa,
                'periode_semester' => $periode
            ]);
            return back()->with('error', 'Gagal memproses evaluasi SVM: ' . $e->getMessage());
        }
    }

    /**
     * Set notifikasi_terkirim = true untuk hasil tertentu (sistem/admin).
     */
    public function updateNotifikasi(HasilKlasifikasi $hasilKlasifikasi)
    {
        abort_if(auth()->user()->role !== 'admin', 403);

        try {
            $hasilKlasifikasi->update(['notifikasi_terkirim' => true]);

            return redirect()->back()
                ->with('success', 'Status notifikasi berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Helper: batasi akses show berdasarkan role.
     */
    private function authorizeShow(HasilKlasifikasi $hasil): void
    {
        $user = auth()->user();
        if ($user->role === 'siswa' && $hasil->id_siswa !== $user->siswa->id) {
            abort(403);
        }
        if ($user->role === 'guru') {
            // Cek apakah siswa yang diklasifikasi adalah siswa binaan guru ini
            $hasil->load('siswa');
            if ($hasil->siswa?->id_guru !== $user->guru->id) {
                abort(403);
            }
        }
    }
}
