<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'tb_guru';

    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'nip',
        'mata_pelajaran',
        'no_telp',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Relasi ──────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'id_guru', 'id');
    }

    public function dataHafalans()
    {
        return $this->hasMany(DataHafalan::class, 'id_guru', 'id');
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'id_guru', 'id');
    }
}
