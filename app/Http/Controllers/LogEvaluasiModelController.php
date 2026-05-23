<?php

namespace App\Http\Controllers;

use App\Models\LogEvaluasiModel;
use App\Models\ModelSvm;
use Illuminate\Http\Request;

class LogEvaluasiModelController extends Controller
{

    /**
     * Tampilkan semua log evaluasi untuk model SVM tertentu (filter by id_model).
     */
    public function index()
    {
        $models = ModelSvm::orderBy('versi_model')->get();
        $query = LogEvaluasiModel::with('modelSvm');

        if (request()->filled('id_model')) {
            $query->where('id_model', request('id_model'));
        }

        $logs = $query->latest('tanggal_evaluasi')->paginate(15);

        return view('admin.log_evaluasi.index', compact('logs', 'models'));
    }

    /**
     * Simpan hasil evaluasi model: akurasi, precision, recall, f1_score, confusion_matrix.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_model' => ['required', 'integer', 'exists:tb_model_svm,id'],
            'akurasi' => ['required', 'numeric', 'min:0', 'max:100'],
            'precision' => ['required', 'numeric', 'min:0', 'max:100'],
            'recall' => ['required', 'numeric', 'min:0', 'max:100'],
            'f1_score' => ['required', 'numeric', 'min:0', 'max:100'],
            'confusion_matrix' => ['required', 'array'],
            'confusion_matrix.*' => ['array'],
        ]);

        try {
            LogEvaluasiModel::create([
                'id_model' => $request->id_model,
                'akurasi' => $request->akurasi,
                'precision' => $request->precision,
                'recall' => $request->recall,
                'f1_score' => $request->f1_score,
                'confusion_matrix' => $request->confusion_matrix, // auto JSON encode oleh cast
                'tanggal_evaluasi' => now(),
            ]);

            return redirect()->route('admin.log-evaluasi.index', ['id_model' => $request->id_model])
                ->with('success', 'Log evaluasi berhasil disimpan.');
        }
        catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menyimpan log evaluasi: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail log evaluasi termasuk render confusion matrix dari JSON.
     */
    public function show(LogEvaluasiModel $logEvaluasiModel)
    {
        $logEvaluasiModel->load('modelSvm');

        // confusion_matrix sudah berupa array (karena cast di Model)
        $confusionMatrix = $logEvaluasiModel->confusion_matrix;
        $labels = ['A', 'B', 'C'];

        return view('admin.log_evaluasi.show', compact('logEvaluasiModel', 'confusionMatrix', 'labels'));
    }
}
