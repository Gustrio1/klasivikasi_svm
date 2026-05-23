<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogEvaluasiModel extends Model
{
    protected $table = 'tb_log_evaluasi_model';

    public $timestamps = false;

    const CREATED_AT = 'tanggal_evaluasi';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_model',
        'akurasi',
        'precision',
        'recall',
        'f1_score',
        'confusion_matrix',
        'tanggal_evaluasi',
    ];

    protected $casts = [
        'akurasi'          => 'float',
        'precision'        => 'float',
        'recall'           => 'float',
        'f1_score'         => 'float',
        'confusion_matrix' => 'array',   // JSON → array
        'tanggal_evaluasi' => 'datetime',
    ];

    // ─── Relasi ──────────────────────────────────────────────
    public function modelSvm()
    {
        return $this->belongsTo(ModelSvm::class, 'id_model', 'id');
    }
}
