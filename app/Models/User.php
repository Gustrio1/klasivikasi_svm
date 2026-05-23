<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tb_users';

    public $timestamps = true;

    // Laravel Auth menggunakan kolom ini untuk login (default: email)
    // Kita override ke 'username'
    protected $rememberTokenName = 'remember_token';

    protected $fillable = [
        'username',
        'password',
        'role',
        'nama_lengkap',
        'email',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
    ];



    // ─── Relasi ──────────────────────────────────────────────
    public function guru()
    {
        return $this->hasOne(Guru::class, 'id_user', 'id');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'id_user', 'id');
    }
}

