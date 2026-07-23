<?php

namespace App\Http\Controllers;

use App\Models\MasterSurah;
use Illuminate\Http\Request;

class MasterSurahController extends Controller
{
    public function index()
    {
        $surahs = MasterSurah::orderBy('nomor_surah')->get();
        return view('admin.master-surah.index', compact('surahs'));
    }

    public function create()
    {
        return view('admin.master-surah.form', ['surah' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_surah' => 'required|integer|min:1|max:114|unique:tb_master_surah,nomor_surah',
            'nama_surah'  => 'required|string|max:100',
            'jumlah_ayat' => 'required|integer|min:1|max:1000',
        ]);

        MasterSurah::create($data + ['is_active' => true]);

        return redirect()->route('admin.master-surah.index')
                         ->with('success', "Surat {$data['nama_surah']} berhasil ditambahkan.");
    }

    public function edit(MasterSurah $masterSurah)
    {
        return view('admin.master-surah.form', ['surah' => $masterSurah]);
    }

    public function update(Request $request, MasterSurah $masterSurah)
    {
        $data = $request->validate([
            'nomor_surah' => "required|integer|min:1|max:114|unique:tb_master_surah,nomor_surah,{$masterSurah->id}",
            'nama_surah'  => 'required|string|max:100',
            'jumlah_ayat' => 'required|integer|min:1|max:1000',
        ]);

        $masterSurah->update($data);

        return redirect()->route('admin.master-surah.index')
                         ->with('success', "Surat {$data['nama_surah']} berhasil diperbarui.");
    }

    public function toggle(MasterSurah $masterSurah)
    {
        $masterSurah->update(['is_active' => !$masterSurah->is_active]);
        $status = $masterSurah->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Surat {$masterSurah->nama_surah} berhasil {$status}.");
    }
}
