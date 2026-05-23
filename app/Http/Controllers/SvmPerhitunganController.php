<?php

namespace App\Http\Controllers;

use App\Models\DataTraining;

class SvmPerhitunganController extends Controller
{
    // ── Hyperparameter Training ──────────────────────────────────────────────
    private float $learningRate = 0.01;   // η  – ukuran langkah gradient descent
    private float $lambda       = 0.001;  // λ  – regularisasi L2 (mencegah overfitting)
    private int   $maxEpoch     = 1000;   // jumlah iterasi training
    private float $tolerance    = 1e-6;   // hentikan jika perubahan loss < tolerance

    // ── State bobot SVM ──────────────────────────────────────────────────────
    // Fitur: x1 = fitur_jumlah_ayat, x2 = fitur_usia, x3 = id_media (encoded)
    private float $w1   = 0.0;
    private float $w2   = 0.0;
    private float $w3   = 0.0;
    private float $bias = 0.0;

    // ── Normalisasi (Min-Max) ────────────────────────────────────────────────
    private float $ayatMin, $ayatMax;
    private float $usiaMin, $usiaMax;
    private float $mediaMin, $mediaMax;

    // =========================================================================
    //  ENTRY POINT
    // =========================================================================
    public function index()
    {
        // 1. Ambil data training valid dari database
        $rawData = DataTraining::where('is_valid', true)
            ->with('mediaHafalan')
            ->get();

        // Fallback jika data kosong
        if ($rawData->isEmpty()) {
            $view = auth()->user()->role === 'admin'
                ? 'admin.klasifikasi.perhitungan'
                : 'guru.klasifikasi.perhitungan';

            return view($view, [
                'hasil'       => [],
                'total'       => 0,
                'benar'       => 0,
                'salah'       => 0,
                'akurasi'     => 0,
                'distribusi'  => ['A' => 0, 'B' => 0, 'C' => 0],
                'trainingLog' => [],
                'cm'          => ['tp' => 0, 'tn' => 0, 'fp' => 0, 'fn' => 0, 'precision' => 0, 'recall' => 0, 'f1' => 0],
                'emptyData'   => true,
            ]);
        }

        // 2. Konversi ke array sederhana
        $dataset = $rawData->map(fn($row) => [
            'nama'        => 'Data #' . $row->id,
            'ayat'        => (int) $row->fitur_jumlah_ayat,
            'usia'        => (int) $row->fitur_usia,
            'media'       => (int) ($row->id_media ?? 1),
            'media_label' => $row->mediaHafalan?->nama_media ?? 'Media #' . $row->id_media,
            'kelas_asli'  => $this->encodeKelas($row->label_kelas),
            'label_asli'  => $row->label_kelas,
        ])->toArray();

        // 3. Hitung min-max dari data DB
        $this->hitungMinMax($dataset);

        // 4. Normalisasi
        $normalized = $this->normalisasiDataset($dataset);

        // 5. Training SVM (Subgradient Descent)
        $trainingLog = $this->trainSVM($normalized);

        // 6. Prediksi & evaluasi
        $hasil   = $this->prediksiDanEvaluasi($normalized);
        $total   = count($hasil);
        $benar   = collect($hasil)->where('benar', true)->count();
        $salah   = $total - $benar;
        $akurasi = $total > 0 ? round(($benar / $total) * 100, 2) : 0;

        // 7. Confusion matrix
        $cm = $this->confusionMatrix($hasil);

        // 8. Distribusi kelas
        $distribusi = [
            'A' => collect($hasil)->where('label_asli', 'A')->count(),
            'B' => collect($hasil)->where('label_asli', 'B')->count(),
            'C' => collect($hasil)->where('label_asli', 'C')->count(),
        ];

        // 9. Pilih view sesuai role
        $view = auth()->user()->role === 'admin'
            ? 'admin.klasifikasi.perhitungan'
            : 'guru.klasifikasi.perhitungan';

        return view($view, compact(
            'hasil', 'total', 'benar', 'salah', 'akurasi',
            'distribusi', 'trainingLog', 'cm'
        ));
    }

    // =========================================================================
    //  Encode label kelas A/B/C ke integer SVM
    //  A = 1 (Sangat Lancar), B = 0 (Sedang), C = -1 (Kurang)
    // =========================================================================
    private function encodeKelas(string $label): int
    {
        return match ($label) {
            'A'     => 1,
            'B'     => 0,
            default => -1,
        };
    }

    // =========================================================================
    //  STEP 1 – Hitung Min & Max fitur dari data DB
    // =========================================================================
    private function hitungMinMax(array $dataset): void
    {
        $ayats  = array_column($dataset, 'ayat');
        $usias  = array_column($dataset, 'usia');
        $medias = array_column($dataset, 'media');

        $this->ayatMin  = (float) min($ayats);
        $this->ayatMax  = (float) max($ayats);
        $this->usiaMin  = (float) min($usias);
        $this->usiaMax  = (float) max($usias);
        $this->mediaMin = (float) min($medias);
        $this->mediaMax = (float) max($medias);
    }

    // =========================================================================
    //  STEP 2 – Normalisasi Min-Max ke rentang [0, 1]
    // =========================================================================
    private function normalisasiDataset(array $dataset): array
    {
        $result = [];
        foreach ($dataset as $row) {
            $x1 = $this->normalize($row['ayat'],  $this->ayatMin,  $this->ayatMax);
            $x2 = $this->normalize($row['usia'],  $this->usiaMin,  $this->usiaMax);
            $x3 = $this->normalize($row['media'], $this->mediaMin, $this->mediaMax);

            $result[] = array_merge($row, [
                'x1' => $x1,
                'x2' => $x2,
                'x3' => $x3,
            ]);
        }
        return $result;
    }

    private function normalize(float $val, float $min, float $max): float
    {
        if ($max === $min) return 0.0;
        return round(($val - $min) / ($max - $min), 6);
    }

    // =========================================================================
    //  STEP 3 – Training SVM dengan Subgradient Descent (Hinge Loss + L2)
    //
    //  Fungsi loss (Hinge Loss):
    //    L = (λ/2)||w||² + (1/n) Σ max(0, 1 − yᵢ·f(xᵢ))
    //
    //  Update rule per sampel:
    //    Jika yᵢ·f(xᵢ) >= 1  (sudah di luar margin):
    //      w ← w − η·λ·w
    //    Jika yᵢ·f(xᵢ) < 1   (salah / di dalam margin):
    //      w ← w − η·(λ·w − yᵢ·xᵢ)
    //      b ← b + η·yᵢ
    // =========================================================================
    private function trainSVM(array $data): array
    {
        $this->w1   = 0.0;
        $this->w2   = 0.0;
        $this->w3   = 0.0;
        $this->bias = 0.0;

        $log      = [];
        $prevLoss = PHP_FLOAT_MAX;

        for ($epoch = 1; $epoch <= $this->maxEpoch; $epoch++) {
            $totalLoss = 0.0;

            foreach ($data as $row) {
                $y  = (float) $row['kelas_asli'];
                $x1 = $row['x1'];
                $x2 = $row['x2'];
                $x3 = $row['x3'];

                $fx        = $this->w1 * $x1 + $this->w2 * $x2 + $this->w3 * $x3 + $this->bias;
                $margin    = $y * $fx;
                $hingeLoss = max(0.0, 1.0 - $margin);
                $totalLoss += $hingeLoss;

                if ($margin < 1.0) {
                    // Sampel melanggar margin → update penuh
                    $this->w1   -= $this->learningRate * ($this->lambda * $this->w1 - $y * $x1);
                    $this->w2   -= $this->learningRate * ($this->lambda * $this->w2 - $y * $x2);
                    $this->w3   -= $this->learningRate * ($this->lambda * $this->w3 - $y * $x3);
                    $this->bias += $this->learningRate * $y;
                } else {
                    // Sampel sudah di luar margin → hanya regularisasi
                    $this->w1 -= $this->learningRate * $this->lambda * $this->w1;
                    $this->w2 -= $this->learningRate * $this->lambda * $this->w2;
                    $this->w3 -= $this->learningRate * $this->lambda * $this->w3;
                }
            }

            // Loss rata-rata + regularisasi
            $regLoss   = ($this->lambda / 2) * ($this->w1 ** 2 + $this->w2 ** 2 + $this->w3 ** 2);
            $totalLoss = round($totalLoss / count($data) + $regLoss, 6);

            // Simpan log setiap 50 epoch
            if ($epoch % 50 === 0 || $epoch === 1) {
                $log[] = [
                    'epoch' => $epoch,
                    'loss'  => $totalLoss,
                    'w1'    => round($this->w1, 6),
                    'w2'    => round($this->w2, 6),
                    'w3'    => round($this->w3, 6),
                    'bias'  => round($this->bias, 6),
                ];
            }

            // Early stopping
            if (abs($prevLoss - $totalLoss) < $this->tolerance) {
                $log[] = [
                    'epoch' => $epoch,
                    'loss'  => $totalLoss,
                    'w1'    => round($this->w1, 6),
                    'w2'    => round($this->w2, 6),
                    'w3'    => round($this->w3, 6),
                    'bias'  => round($this->bias, 6),
                    'note'  => 'Early stopping (konvergen)',
                ];
                break;
            }

            $prevLoss = $totalLoss;
        }

        return $log;
    }

    // =========================================================================
    //  STEP 4 – Prediksi & Evaluasi setelah Training
    // =========================================================================
    private function prediksiDanEvaluasi(array $data): array
    {
        $hasil = [];

        foreach ($data as $row) {
            $x1 = $row['x1'];
            $x2 = $row['x2'];
            $x3 = $row['x3'];

            // f(x) = w1·x1 + w2·x2 + w3·x3 + bias
            $fx = round(
                $this->w1 * $x1 + $this->w2 * $x2 + $this->w3 * $x3 + $this->bias,
                6
            );

            $langkah = sprintf(
                'f(x) = %.4f×%.4f + %.4f×%.4f + %.4f×%.4f + (%.4f) = %.6f',
                $this->w1, $x1,
                $this->w2, $x2,
                $this->w3, $x3,
                $this->bias,
                $fx
            );

            // Decode kembali ke label kelas A/B/C
            $kelas_prediksi = $fx >= 0.5 ? 'A' : ($fx >= -0.5 ? 'B' : 'C');
            $benar          = $kelas_prediksi === $row['label_asli'];

            $hasil[] = array_merge($row, [
                'fx'             => $fx,
                'langkah'        => $langkah,
                'kelas_prediksi' => $kelas_prediksi,
                'label_prediksi' => 'Kelas ' . $kelas_prediksi,
                'benar'          => $benar,
            ]);
        }

        return $hasil;
    }

    // =========================================================================
    //  STEP 5 – Confusion Matrix (berbasis A/B/C)
    //  Untuk SVM multiclass sederhana: TP = prediksi == asli
    // =========================================================================
    private function confusionMatrix(array $hasil): array
    {
        $tp = $tn = $fp = $fn = 0;

        foreach ($hasil as $h) {
            $actual    = $h['label_asli'];
            $predicted = $h['kelas_prediksi'];

            // Kelas A dianggap positif untuk metrik binary
            if ($actual === 'A' && $predicted === 'A') $tp++;
            elseif ($actual !== 'A' && $predicted !== 'A') $tn++;
            elseif ($actual !== 'A' && $predicted === 'A') $fp++;
            elseif ($actual === 'A' && $predicted !== 'A') $fn++;
        }

        $precision = ($tp + $fp) > 0 ? round($tp / ($tp + $fp), 4) : 0;
        $recall    = ($tp + $fn) > 0 ? round($tp / ($tp + $fn), 4) : 0;
        $f1        = ($precision + $recall) > 0
                     ? round(2 * $precision * $recall / ($precision + $recall), 4)
                     : 0;

        return compact('tp', 'tn', 'fp', 'fn', 'precision', 'recall', 'f1');
    }

    // =========================================================================
    //  PUBLIC GETTER – untuk keperluan unit test / ekspor bobot
    // =========================================================================
    public function getWeights(): array
    {
        return [
            'w1'   => $this->w1,
            'w2'   => $this->w2,
            'w3'   => $this->w3,
            'bias' => $this->bias,
        ];
    }
}