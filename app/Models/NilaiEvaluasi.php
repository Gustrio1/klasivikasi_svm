<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiEvaluasi extends Model
{
    protected $table = 'tb_nilai_evaluasi';

    public $timestamps = false;

    const CREATED_AT = 'tanggal_evaluasi';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_hafalan',
        'catatan_guru',
        'tanggal_evaluasi',
    ];

    protected $casts = [
        'tanggal_evaluasi' => 'datetime',
    ];

    // ─── Relasi ──────────────────────────────────────────────
    public function dataHafalan()
    {
        return $this->belongsTo(DataHafalan::class, 'id_hafalan', 'id');
    }
}
