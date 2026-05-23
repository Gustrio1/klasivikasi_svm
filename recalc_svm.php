<?php

use App\Models\HasilKlasifikasi;
use Illuminate\Support\Facades\DB;

$hasilKlasifikasi = HasilKlasifikasi::with(['dataHafalan.siswa'])->get();

$W1 = 1.5;
$W2 = -0.2;
$W3 = 0.8;
$b  = -1.2;

foreach ($hasilKlasifikasi as $hk) {
    $hafalan = $hk->dataHafalan;
    if (!$hafalan || !$hafalan->siswa) continue;

    $jumlah_ayat = $hafalan->jumlah_ayat;
    $usia = $hafalan->siswa->tanggal_lahir ? date('Y') - $hafalan->siswa->tanggal_lahir : 15;
    $id_media = $hafalan->id_media;

    $fx = ($W1 * $jumlah_ayat) + ($W2 * $usia) + ($W3 * $id_media) + $b;
    
    $kelas_prediksi = $fx >= 0 ? 'Lulus' : 'Tidak Lulus';
    
    $margin = abs($fx);
    $confidence = min(0.5 + ($margin / 10.0), 0.99);
    
    $vector = [
        'W1' => $W1,
        'W2' => $W2,
        'W3' => $W3,
        'bias' => $b,
        'fx' => round($fx, 4),
        'X1_ayat' => $jumlah_ayat,
        'X2_usia' => $usia,
        'X3_media' => $id_media,
        'rumus_fx' => "({$W1} * {$jumlah_ayat}) + ({$W2} * {$usia}) + ({$W3} * {$id_media}) + ({$b}) = " . round($fx, 4)
    ];

    $hk->update([
        'kelas_prediksi' => $kelas_prediksi,
        'confidence_score' => $confidence,
        'vector_svm' => json_encode($vector)
    ]);
}

echo "Berhasil update " . count($hasilKlasifikasi) . " data klasifikasi.\n";
