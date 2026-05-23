<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'tb_laporan';

    public $timestamps = false;

    const CREATED_AT = 'tanggal_cetak';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_siswa',
        'id_guru',
        'judul_laporan',
        'periode',
        'file_path',
        'tanggal_cetak',
    ];

    protected $casts = [
        'tanggal_cetak' => 'datetime',
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
}
