<?php

namespace App\Http\Controllers;

use App\Models\DataHafalan;
use App\Models\HasilKlasifikasi;
use App\Models\NilaiEvaluasi;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    /**
     * Dashboard dinamis berdasarkan role user yang login.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'siswa') {
            return $this->siswaDashboard($user);
        } elseif ($user->role === 'guru') {
            return $this->guruDashboard($user);
        } else {
            return $this->adminDashboard($user);
        }
    }

    // ─── Siswa Dashboard ──────────────────────────────────────────
    private function siswaDashboard($user)
    {
        $siswa    = $user->siswa;
        $siswaId  = $siswa?->id;

        if (!$siswaId) {
            return view('siswa.dashboard', [
                'totalHafalan'         => 0,
                'kelasTermakhir'       => null,
                'nilaiRataRata'        => 0,
                'hafalanTerakhir'      => collect(),
                'distribusiKelas'      => collect(),
            ]);
        }

        $totalHafalan = DataHafalan::where('id_siswa', $siswaId)->count();

        $kelasTermakhir = HasilKlasifikasi::where('id_siswa', $siswaId)
            ->latest('tanggal_klasifikasi')
            ->value('kelas_prediksi');

        $nilaiRataRata = round(
            NilaiEvaluasi::whereHas('dataHafalan', fn($q) => $q->where('id_siswa', $siswaId))
                ->avg('nilai_total') ?? 0,
            2
        );



        $hafalanTerakhir = DataHafalan::with(['nilaiEvaluasi'])
            ->where('id_siswa', $siswaId)
            ->latest('tanggal_input')
            ->limit(5)
            ->get();

        $distribusiKelas = HasilKlasifikasi::where('id_siswa', $siswaId)
            ->selectRaw('kelas_prediksi, count(*) as total')
            ->groupBy('kelas_prediksi')
            ->pluck('total', 'kelas_prediksi');



        return view('siswa.dashboard', compact(
            'totalHafalan',
            'kelasTermakhir',
            'nilaiRataRata',
            'hafalanTerakhir',
            'distribusiKelas'
        ));
    }

    // ─── Guru Dashboard ───────────────────────────────────────────
    private function guruDashboard($user)
    {
        $guru   = $user->guru;
        $guruId = $guru?->id;

        $totalSiswa   = \App\Models\Siswa::where('id_guru', $guruId)->count();
        $totalHafalan = DataHafalan::where('id_guru', $guruId)->count();
        $hariIni      = DataHafalan::where('id_guru', $guruId)
            ->whereDate('tanggal_input', today())->count();
        $perluDievaluasi = DataHafalan::where('id_guru', $guruId)
            ->doesntHave('nilaiEvaluasi')->count();

        $hafalanTerbaru = DataHafalan::with(['siswa.user', 'nilaiEvaluasi'])
            ->where('id_guru', $guruId)
            ->latest('tanggal_input')
            ->limit(5)
            ->get();

        return view('guru.dashboard', compact(
            'totalSiswa', 'totalHafalan', 'hariIni', 'perluDievaluasi', 'hafalanTerbaru'
        ));
    }

    // ─── Admin Dashboard ──────────────────────────────────────────
    private function adminDashboard($user)
    {
        $totalUser    = \App\Models\User::count();
        $totalGuru    = \App\Models\Guru::count();
        $totalSiswa   = \App\Models\Siswa::count();
        $totalHafalan = DataHafalan::count();
        $modelAktif   = \App\Models\ModelSvm::where('is_active', true)->first();

        return view('admin.dashboard', compact(
            'totalUser', 'totalGuru', 'totalSiswa', 'totalHafalan', 'modelAktif'
        ));
    }
}
