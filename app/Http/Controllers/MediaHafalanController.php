<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaHafalanRequest;
use App\Http\Requests\UpdateMediaHafalanRequest;
use App\Models\MediaHafalan;

class MediaHafalanController extends Controller
{

    /**
     * Tampilkan semua media; support filter jenis_media dan kelas_target.
     */
    public function index()
    {
        $query = MediaHafalan::query();

        if (request('jenis_media')) {
            $query->where('jenis_media', request('jenis_media'));
        }
        $medias = $query->where('is_active', true)
            ->orderBy('nama_media')
            ->paginate(15);

        $view = match (auth()->user()->role) {
                'admin' => 'admin.media_hafalan.index',
                'guru' => 'guru.media_hafalan.index',
                'siswa' => 'siswa.media_hafalan.index',
                default => 'media_hafalan.index',
            };

        return view($view, compact('medias'));
    }

    /**
     * Form tambah media baru (admin only).
     */
    public function create()
    {
        $view = match (auth()->user()->role) {
                'admin' => 'admin.media_hafalan.create',
                'guru' => 'guru.media_hafalan.create',
                default => 'media_hafalan.create',
            };
        return view($view);
    }

    /**
     * Simpan media baru; url_link di-null-kan otomatis jika jenis cetak.
     */
    public function store(StoreMediaHafalanRequest $request)
    {
        try {
            $data = $request->validated();
            $data['is_active'] = true;
            $data['tanggal_input'] = now();

            // Pastikan url_link null jika media cetak
            if ($data['jenis_media'] === 'cetak') {
                $data['url_link'] = null;
            }

            MediaHafalan::create($data);

            $route = match (auth()->user()->role) {
                    'admin' => 'admin.media-hafalan.index',
                    'guru' => 'guru.media-hafalan.index',
                    'siswa' => 'siswa.media-hafalan.index',
                    default => 'media-hafalan.index',
                };

            return redirect()->route($route)
                ->with('success', 'Media hafalan berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan media: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail media hafalan.
     */
    public function show(MediaHafalan $mediaHafalan)
    {
        $view = match (auth()->user()->role) {
                'admin' => 'admin.media_hafalan.show',
                'guru' => 'guru.media_hafalan.show',
                'siswa' => 'siswa.media_hafalan.show',
                default => 'media_hafalan.show',
            };
        return view($view, compact('mediaHafalan'));
    }

    /**
     * Form edit media (admin only).
     */
    public function edit(MediaHafalan $mediaHafalan)
    {
        $view = match (auth()->user()->role) {
                'admin' => 'admin.media_hafalan.edit',
                'guru' => 'guru.media_hafalan.edit',
                default => 'media_hafalan.edit',
            };
        return view($view, compact('mediaHafalan'));
    }

    /**
     * Update media dengan validasi url_link sama seperti store.
     */
    public function update(UpdateMediaHafalanRequest $request, MediaHafalan $mediaHafalan)
    {
        try {
            $data = $request->validated();

            if (isset($data['jenis_media']) && $data['jenis_media'] === 'cetak') {
                $data['url_link'] = null;
            }

            $mediaHafalan->update($data);

            $route = match (auth()->user()->role) {
                    'admin' => 'admin.media-hafalan.index',
                    'guru' => 'guru.media-hafalan.index',
                    'siswa' => 'siswa.media-hafalan.index',
                    default => 'media-hafalan.index',
                };

            return redirect()->route($route)
                ->with('success', 'Media hafalan berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui media: ' . $e->getMessage());
        }
    }

    /**
     * Non-aktifkan media (set is_active = false).
     */
    public function destroy(MediaHafalan $mediaHafalan)
    {
        try {
            $mediaHafalan->update(['is_active' => false]);

            $route = match (auth()->user()->role) {
                    'admin' => 'admin.media-hafalan.index',
                    'guru' => 'guru.media-hafalan.index',
                    'siswa' => 'siswa.media-hafalan.index',
                    default => 'media-hafalan.index',
                };

            return redirect()->route($route)
                ->with('success', 'Media hafalan berhasil dinonaktifkan.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal menonaktifkan media: ' . $e->getMessage());
        }
    }
}
