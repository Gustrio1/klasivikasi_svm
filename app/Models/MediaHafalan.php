<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaHafalan extends Model
{
    protected $table = 'tb_media_hafalan';

    public $timestamps = false;

    const CREATED_AT = 'tanggal_input';
    const UPDATED_AT = null;

    protected $fillable = [
        'nama_media',
        'jenis_media',
        'kelas_target',
        'url_link',
        'format_file',
        'deskripsi',
        'tips_belajar',
        'alasan_rekomendasi',
        'is_active',
        'tanggal_input',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'tanggal_input' => 'datetime',
    ];

    // ─── Relasi ──────────────────────────────────────────────

}
