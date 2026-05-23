<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelSvm extends Model
{
    protected $table = 'tb_model_svm';

    public $timestamps = false;

    const CREATED_AT = 'tanggal_training';
    const UPDATED_AT = null;

    protected $fillable = [
        'versi_model',
        'kernel_type',
        'parameter_C',
        'parameter_gamma',
        'akurasi_model',
        'is_active',
        'tanggal_training',
    ];

    protected $casts = [
        'parameter_C'     => 'float',
        'parameter_gamma' => 'float',
        'akurasi_model'   => 'float',
        'is_active'       => 'boolean',
        'tanggal_training' => 'datetime',
    ];

    // ─── Relasi ──────────────────────────────────────────────
    public function hasilKlasifikasis()
    {
        return $this->hasMany(HasilKlasifikasi::class, 'id_model', 'id');
    }

    public function logEvaluasiModels()
    {
        return $this->hasMany(LogEvaluasiModel::class, 'id_model', 'id');
    }
}
