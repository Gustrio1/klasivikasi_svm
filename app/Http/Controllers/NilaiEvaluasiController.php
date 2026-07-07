<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNilaiEvaluasiRequest;
use App\Http\Requests\UpdateNilaiEvaluasiRequest;
use App\Models\NilaiEvaluasi;
use App\Models\DataHafalan;

class NilaiEvaluasiController extends Controller
{


    /**
     * Guru input nilai evaluasi; hitung nilai_total otomatis = (makhraj + fashohah) / 2.
     */
    public function store(StoreNilaiEvaluasiRequest $request)
    {
        abort_if(auth()->user()->role !== 'guru', 403);

        try {
            NilaiEvaluasi::create([
                'id_hafalan' => $request->id_hafalan,
                'catatan_guru' => $request->catatan_guru,
                'tanggal_evaluasi' => now(),
            ]);

            return redirect()->route('guru.hafalan.show', $request->id_hafalan)
                ->with('success', 'Evaluasi hafalan berhasil disimpan.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menyimpan evaluasi: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan nilai evaluasi beserta data hafalan terkait.
     */
    public function show(NilaiEvaluasi $nilaiEvaluasi)
    {
        $user = auth()->user();

        $nilaiEvaluasi->load(['dataHafalan.siswa.user', 'dataHafalan.guru.user']);

        // Siswa hanya boleh lihat nilai miliknya
        if ($user->role === 'siswa') {
            abort_if($nilaiEvaluasi->dataHafalan->id_siswa !== $user->siswa->id, 403);
        }

        return view('nilai_evaluasi.show', compact('nilaiEvaluasi'));
    }

    /**
     * Update nilai evaluasi; recalculate nilai_total.
     */
    public function update(UpdateNilaiEvaluasiRequest $request, NilaiEvaluasi $nilaiEvaluasi)
    {
        abort_if(auth()->user()->role !== 'guru', 403);

        try {
            $nilaiEvaluasi->update([
                'catatan_guru' => $request->catatan_guru ?? $nilaiEvaluasi->catatan_guru,
            ]);

            return redirect()->route('guru.hafalan.show', $nilaiEvaluasi->id_hafalan)
                ->with('success', 'Evaluasi hafalan berhasil diperbarui.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui evaluasi: ' . $e->getMessage());
        }
    }
}
