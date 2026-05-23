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
        'nilai_makhraj',
        'nilai_fashohah',
        'nilai_total',
        'catatan_guru',
        'tanggal_evaluasi',
    ];

    protected $casts = [
        'nilai_makhraj'   => 'float',
        'nilai_fashohah'  => 'float',
        'nilai_total'     => 'float',
        'tanggal_evaluasi' => 'datetime',
    ];

    // ─── Relasi ──────────────────────────────────────────────
    public function dataHafalan()
    {
        return $this->belongsTo(DataHafalan::class, 'id_hafalan', 'id');
    }
}
