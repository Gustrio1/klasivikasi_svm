<?php

namespace App\Http\Controllers;

use App\Models\ModelSvm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModelSvmController extends Controller
{

    /**
     * Tampilkan semua versi model SVM beserta log evaluasi terakhir.
     */
    public function index()
    {
        $models = ModelSvm::with([
            'logEvaluasiModels' => fn($q) => $q->latest('tanggal_evaluasi')->limit(1),
        ])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.model_svm.index', compact('models'));
    }

    /**
     * Form tambah record model baru.
     */
    public function create()
    {
        return view('admin.model_svm.create');
    }

    /**
     * Simpan record model SVM baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'versi_model' => ['required', 'string', 'max:50'],
            'kernel_type' => ['required', 'in:rbf,linear'],
            'parameter_C' => ['required', 'numeric', 'min:0'],
            'parameter_gamma' => ['required', 'numeric', 'min:0'],
            'akurasi_model' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        try {
            ModelSvm::create(array_merge($request->validated(), [
                'is_active' => false,
                'tanggal_training' => now(),
            ]));

            return redirect()->route('admin.model-svm.index')
                ->with('success', 'Model SVM berhasil ditambahkan.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan model: ' . $e->getMessage());
        }
    }

    /**
     * Detail model SVM.
     */
    public function show(ModelSvm $modelSvm)
    {
        $modelSvm->load('logEvaluasiModels');

        return view('admin.model_svm.show', compact('modelSvm'));
    }

    /**
     * Aktifkan model tertentu dan non-aktifkan semua model lain dalam satu transaksi DB.
     */
    public function aktivasi(ModelSvm $modelSvm)
    {
        try {
            DB::transaction(function () use ($modelSvm) {
                // Non-aktifkan semua model
                ModelSvm::query()->update(['is_active' => false]);
                // Aktifkan model yang dipilih
                $modelSvm->update(['is_active' => true]);
            });

            return redirect()->route('admin.model-svm.index')
                ->with('success', "Model versi {$modelSvm->versi_model} berhasil diaktifkan.");
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal mengaktifkan model: ' . $e->getMessage());
        }
    }

    /**
     * Hapus model hanya jika is_active = false.
     */
    public function destroy(ModelSvm $modelSvm)
    {
        if ($modelSvm->is_active) {
            return back()->with('error', 'Model yang sedang aktif tidak dapat dihapus. Aktifkan model lain terlebih dahulu.');
        }

        try {
            $modelSvm->delete();

            return redirect()->route('admin.model-svm.index')
                ->with('success', 'Model SVM berhasil dihapus.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus model: ' . $e->getMessage());
        }
    }
}
