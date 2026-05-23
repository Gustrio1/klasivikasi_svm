<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{

    /**
     * Admin: semua laporan. Guru: laporan yang dibuat. Siswa: laporan miliknya.
     */
    public function index()
    {
        $user = auth()->user();
        $query = Laporan::with(['siswa.user', 'guru.user']);

        if ($user->role === 'guru') {
            $query->where('id_guru', $user->guru->id);
        }
        elseif ($user->role === 'siswa') {
            $query->where('id_siswa', $user->siswa->id);
        }

        $laporan = $query->latest('tanggal_cetak')->paginate(15);

        $view = match ($user->role) {
                'siswa' => 'siswa.laporan.index',
                'guru' => 'guru.laporan.index',
                'admin' => 'admin.laporan.index',
                default => 'admin.laporan.index',
            };
        return view($view, compact('laporan'));
    }

    /**
     * Form generate laporan (admin/guru).
     */
    public function create()
    {
        abort_if(!in_array(auth()->user()->role, ['admin', 'guru']), 403);

        $siswas = Siswa::with('user')->get();
        $gurus = Guru::with('user')->where('is_active', true)->get();

        $view = match (auth()->user()->role) {
                'admin' => 'admin.laporan.create',
                'guru' => 'guru.laporan.create',
                default => 'admin.laporan.create',
            };
        return view($view, compact('siswas', 'gurus'));
    }

    /**
     * Generate laporan PDF, simpan ke storage/app/laporan/, catat path ke DB.
     */
    public function store(Request $request)
    {
        abort_if(!in_array(auth()->user()->role, ['admin', 'guru']), 403);

        $request->validate([
            'id_siswa' => ['required', 'integer', 'exists:tb_siswa,id'],
            'judul_laporan' => ['required', 'string', 'max:255'],
            'periode' => ['required', 'string', 'max:100'],
        ]);

        try {
            $siswa = Siswa::with([
                'user',
                'guru.user',
                'hasilKlasifikasis.modelSvm'
            ])->findOrFail($request->id_siswa);

            $idGuru = (auth()->user()->role === 'guru')
                ? auth()->user()->guru->id
                : $request->id_guru;

            // Data lengkap untuk template PDF
            $guru = $siswa->guru;
            $hafalan = $siswa->dataHafalans;

            // Laporan template tetap di satu tempat (atau disesuaikan)
            $pdf = Pdf::loadView('admin.laporan.template', [
                'siswa' => $siswa,
                'guru' => $guru,
                'hafalan' => $hafalan,
                'laporan' => (object)[
                    'judul_laporan' => $request->judul_laporan,
                    'periode' => $request->periode
                ],
                'judul' => $request->judul_laporan,
                'periode' => $request->periode,
            ])->setPaper('a4', 'portrait');

            // Nama file unik
            $fileName = 'laporan_' . $siswa->id . '_' . now()->format('Ymd_His') . '.pdf';
            $filePath = 'laporan/' . $fileName;

            // Simpan ke storage/app/laporan/
            Storage::put($filePath, $pdf->output());

            // Catat ke database
            Laporan::create([
                'id_siswa' => $siswa->id,
                'id_guru' => $idGuru,
                'judul_laporan' => $request->judul_laporan,
                'periode' => $request->periode,
                'file_path' => $filePath,
                'tanggal_cetak' => now(),
            ]);

            return redirect()->route(auth()->user()->role . '.laporan.index')
                ->with('success', 'Laporan berhasil di-generate dan disimpan.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal membuat laporan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail laporan dan tombol download PDF.
     */
    public function show(Laporan $laporan)
    {
        $this->authorizeAccess($laporan);
        $laporan->load(['siswa.user', 'guru.user']);

        $view = match (auth()->user()->role) {
                'siswa' => 'siswa.laporan.show',
                'guru' => 'guru.laporan.show',
                'admin' => 'admin.laporan.show',
                default => 'admin.laporan.show',
            };
        return view($view, compact('laporan'));
    }

    /**
     * Stream file PDF dari storage ke browser (inline preview).
     */
    public function download(Laporan $laporan)
    {
        $this->authorizeAccess($laporan);

        abort_unless(Storage::exists($laporan->file_path), 404, 'File PDF tidak ditemukan.');

        return response()->file(
            Storage::path($laporan->file_path),
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($laporan->file_path) . '"',
        ]
        );
    }

    /**
     * Hapus record laporan dan file PDF dari storage (admin/guru).
     */
    public function destroy(Laporan $laporan)
    {
        abort_if(!in_array(auth()->user()->role, ['admin', 'guru']), 403);

        // Guru hanya bisa hapus laporan yang dia buat
        if (auth()->user()->role === 'guru') {
            abort_if($laporan->id_guru !== auth()->user()->guru->id, 403);
        }

        try {
            // Hapus file dari storage
            if (Storage::exists($laporan->file_path)) {
                Storage::delete($laporan->file_path);
            }

            $laporan->delete();

            return redirect()->route(auth()->user()->role . '.laporan.index')
                ->with('success', 'Laporan berhasil dihapus.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    /**
     * Helper: otorisasi akses laporan berdasarkan role.
     */
    private function authorizeAccess(Laporan $laporan): void
    {
        $user = auth()->user();
        if ($user->role === 'siswa' && $laporan->id_siswa !== $user->siswa->id) {
            abort(403);
        }
        if ($user->role === 'guru' && $laporan->id_guru !== $user->guru->id) {
            abort(403);
        }
    }
}
