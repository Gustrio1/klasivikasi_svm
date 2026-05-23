<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataHafalan extends Model
{
    protected $table = 'tb_data_hafalan';

    public $timestamps = false;

    const CREATED_AT = 'tanggal_input';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_siswa',
        'id_guru',
        'id_media',
        'nama_surah',
        'jumlah_ayat',
        'periode_semester',
        'tanggal_input',
    ];

    protected $casts = [
        'jumlah_ayat' => 'integer',
        'tanggal_input' => 'datetime',
    ];

    // ─── Relasi ──────────────────────────────────────────────
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id');
    }

    public function media()
    {
        return $this->belongsTo(MediaHafalan::class, 'id_media', 'id');
    }

    public function nilaiEvaluasi()
    {
        return $this->hasOne(NilaiEvaluasi::class, 'id_hafalan', 'id');
    }

}
