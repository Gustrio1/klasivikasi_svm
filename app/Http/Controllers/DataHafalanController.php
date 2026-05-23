<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDataHafalanRequest;
use App\Http\Requests\UpdateDataHafalanRequest;
use App\Models\DataHafalan;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

class DataHafalanController extends Controller
{

    /**
     * Guru: tampil hafalan siswa yang diampu. Siswa: tampil hafalan miliknya saja.
     */
    public function index()
    {
        $user = auth()->user();

        $query = DataHafalan::with(['siswa.user', 'guru.user', 'nilaiEvaluasi']);

        if ($user->role === 'guru') {
            $query->where('id_guru', $user->guru->id);
            $view = 'guru.hafalan.index';
        } else {
            $query->where('id_siswa', $user->siswa->id);
            $view = 'siswa.hafalan.index';
        }

        $hafalan = $query->latest('tanggal_input')->paginate(15);

        return view($view, compact('hafalan'));
    }

    /**
     * Form input hafalan baru (guru only).
     */
    public function create()
    {
        abort_if(auth()->user()->role !== 'guru', 403);

        $siswas = Siswa::with('user')
            ->where('id_guru', auth()->user()->guru->id)
            ->get();
        $medias = \App\Models\MediaHafalan::where('is_active', true)
            ->orderBy('nama_media')->get();

        $view = auth()->user()->role === 'guru' ? 'guru.hafalan.create' : 'siswa.hafalan.create';
        return view($view, compact('siswas', 'medias'));
    }

    /**
     * Simpan hafalan baru dan langsung proses klasifikasi SVM otomatis.
     */
    public function store(StoreDataHafalanRequest $request)
    {
        abort_if(auth()->user()->role !== 'guru', 403);

        try {
            $hafalan = DB::transaction(function () use ($request) {
                return DataHafalan::create([
                    'id_siswa' => $request->id_siswa,
                    'id_guru' => auth()->user()->guru->id,
                    'id_media' => $request->id_media,
                    'nama_surah' => $request->nama_surah,
                    'jumlah_ayat' => $request->jumlah_ayat,
                    'periode_semester' => $request->periode_semester,
                    'tanggal_input' => now(),
                ]);
            });

            // Klasifikasi sekarang dilakukan per semester, bukan per setoran harian.
            // app(HasilKlasifikasiController::class)->klasifikasi($hafalan);

            return redirect()->route('guru.hafalan.index')
                ->with('success', 'Data hafalan berhasil disimpan dan klasifikasi SVM sedang diproses.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menyimpan data hafalan: ' . $e->getMessage());
        }
    }

    /**
     * Detail hafalan beserta nilai evaluasi dan hasil klasifikasi.
     */
    public function show(DataHafalan $dataHafalan)
    {
        $this->authorizeAccess($dataHafalan);

        $dataHafalan->load([
            'siswa.user',
            'guru.user',
            'nilaiEvaluasi',
        ]);

        $view = auth()->user()->role === 'guru' ? 'guru.hafalan.show' : 'siswa.hafalan.show';
        return view($view, ['hafalan' => $dataHafalan]);
    }

    /**
     * Form edit hafalan (guru only, hanya jika belum ada hasil klasifikasi).
     */
    public function edit(DataHafalan $dataHafalan)
    {
        abort_if(auth()->user()->role !== 'guru', 403);
        $siswas = Siswa::with('user')
            ->where('id_guru', auth()->user()->guru->id)
            ->get();
        $medias = \App\Models\MediaHafalan::where('is_active', true)
            ->orderBy('nama_media')->get();

        $view = auth()->user()->role === 'guru' ? 'guru.hafalan.edit' : 'siswa.hafalan.edit';
        return view($view, compact('dataHafalan', 'siswas', 'medias'));
    }

    /**
     * Update hafalan, hanya diizinkan jika belum ada hasil klasifikasi.
     */
    public function update(UpdateDataHafalanRequest $request, DataHafalan $dataHafalan)
    {
        abort_if(auth()->user()->role !== 'guru', 403);
        try {
            DB::transaction(function () use ($request, $dataHafalan) {
                // Jangan hapus klasifikasi, karena klasifikasi sekarang per semester, bukan per hafalan tunggal

                $dataHafalan->update([
                    'nama_surah' => $request->nama_surah ?? $dataHafalan->nama_surah,
                    'jumlah_ayat' => $request->jumlah_ayat,
                    'id_media' => $request->id_media ?? $dataHafalan->id_media,
                    'periode_semester' => $request->periode_semester,
                ]);
            });

            return redirect()->route('guru.hafalan.index')
                ->with('success', 'Data hafalan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui data hafalan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus hafalan beserta nilai evaluasi dan klasifikasi terkait (cascade).
     */
    public function destroy(DataHafalan $dataHafalan)
    {
        abort_if(auth()->user()->role !== 'guru', 403);

        try {
            DB::transaction(function () use ($dataHafalan) {

                $dataHafalan->nilaiEvaluasi?->delete();
                $dataHafalan->delete();
            });

            return redirect()->route('guru.hafalan.index')
                ->with('success', 'Data hafalan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data hafalan: ' . $e->getMessage());
        }
    }

    /**
     * Helper: batasi akses berdasarkan role.
     */
    private function authorizeAccess(DataHafalan $dataHafalan): void
    {
        $user = auth()->user();
        if ($user->role === 'siswa' && $dataHafalan->id_siswa !== $user->siswa->id) {
            abort(403);
        }
        if ($user->role === 'guru' && $dataHafalan->id_guru !== $user->guru->id) {
            abort(403);
        }
    }
}
