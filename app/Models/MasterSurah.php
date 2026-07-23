<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSurah extends Model
{
    protected $table = 'tb_master_surah';

    protected $fillable = [
        'nomor_surah',
        'nama_surah',
        'jumlah_ayat',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** Hanya surat yang aktif */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    /** Label untuk dropdown: "An-Naba' (40)" */
    public function getLabelAttribute(): string
    {
        return "{$this->nama_surah} ({$this->jumlah_ayat})";
    }
}
