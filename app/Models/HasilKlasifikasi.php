<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilKlasifikasi extends Model
{
    protected $table = 'tb_hasil_klasifikasi';

    public $timestamps = false;

    const CREATED_AT = 'tanggal_klasifikasi';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_siswa',
        'periode_semester',
        'total_surah',
        'id_model',
        'kelas_prediksi',
        'confidence_score',
        'media_input',
        'notifikasi_terkirim',
        'tanggal_klasifikasi',
        'vector_svm',
    ];

    protected $casts = [
        'total_surah'         => 'integer',
        'confidence_score'    => 'float',
        'notifikasi_terkirim' => 'boolean',
        'tanggal_klasifikasi' => 'datetime',
        'vector_svm'          => 'array',   // JSON → array
    ];

    // ─── Relasi ──────────────────────────────────────────────

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id');
    }

    public function modelSvm()
    {
        return $this->belongsTo(ModelSvm::class, 'id_model', 'id');
    }


}
