<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataTraining extends Model
{
    protected $table = 'tb_data_training';

    public $timestamps = false;

    const CREATED_AT = 'tanggal_input';
    const UPDATED_AT = null;

    protected $fillable = [
        'fitur_total_surah',
        'fitur_usia',
        'id_media',
        'label_kelas',
        'sumber_data',
        'is_valid',
        'tanggal_input',
    ];

    protected $casts = [
        'fitur_total_surah' => 'integer',
        'fitur_usia'        => 'integer',
        'id_media'          => 'integer',
        'is_valid'          => 'boolean',
        'tanggal_input'     => 'datetime',
    ];

    /**
     * Relasi ke media hafalan yang digunakan sebagai fitur SVM.
     */
    public function mediaHafalan(): BelongsTo
    {
        return $this->belongsTo(MediaHafalan::class , 'id_media');
    }
}
