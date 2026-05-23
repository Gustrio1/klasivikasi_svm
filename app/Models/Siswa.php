<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'tb_siswa';

    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'id_guru',
        'nisn',
        'kelas',
        'jenis_kelamin',
        'tanggal_lahir',
    ];

    protected $casts = [
        'tanggal_lahir' => 'integer',
    ];

    // ─── Relasi ──────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id');
    }

    public function dataHafalans()
    {
        return $this->hasMany(DataHafalan::class, 'id_siswa', 'id');
    }

    public function hasilKlasifikasis()
    {
        return $this->hasMany(HasilKlasifikasi::class, 'id_siswa', 'id');
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'id_siswa', 'id');
    }
}
