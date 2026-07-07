<?php

namespace Database\Seeders;

use App\Models\DataHafalan;
use App\Models\DataTraining;
use App\Models\MediaHafalan;
use App\Models\ModelSvm;
use App\Models\NilaiEvaluasi;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DataHafalanMassiveSeeder extends Seeder
{
    // ── Daftar 38 Surah Juz Amma dengan jumlah ayat asli ──────────────────
    private array $surahPool = [
        // Surah pendek (≤7 ayat) → semua profil bisa
        ['nama' => 'Al-Asr',       'ayat' => 3],
        ['nama' => 'Al-Kautsar',   'ayat' => 3],
        ['nama' => 'An-Nasr',      'ayat' => 3],
        ['nama' => 'Al-Ikhlas',    'ayat' => 4],
        ['nama' => 'Quraisy',      'ayat' => 4],
        ['nama' => 'Al-Falaq',     'ayat' => 5],
        ['nama' => 'Al-Fil',       'ayat' => 5],
        ['nama' => 'Al-Masad',     'ayat' => 5],
        ['nama' => 'An-Nas',       'ayat' => 6],
        ['nama' => 'Al-Kafirun',   'ayat' => 6],
        ['nama' => 'Al-Fatihah',   'ayat' => 7],
        ['nama' => 'Al-Maun',      'ayat' => 7],
        // Surah sedang (8–20 ayat) → profil sedang & mahir
        ['nama' => 'Az-Zalzalah',  'ayat' => 8],
        ['nama' => 'Al-Bayyinah',  'ayat' => 8],
        ['nama' => 'Al-Humazah',   'ayat' => 9],
        ['nama' => 'Ad-Duha',      'ayat' => 11],
        ['nama' => 'Al-Qariah',    'ayat' => 11],
        ['nama' => 'Al-Adiyat',    'ayat' => 11],
        ['nama' => 'At-Takatsur',  'ayat' => 8],
        ['nama' => 'Al-Insyirah',  'ayat' => 8],
        ['nama' => 'At-Tin',       'ayat' => 8],
        ['nama' => 'Al-Qadr',      'ayat' => 5],
        ['nama' => 'Asy-Syams',    'ayat' => 15],
        ['nama' => 'At-Tariq',     'ayat' => 17],
        ['nama' => 'Al-Ala',       'ayat' => 19],
        ['nama' => 'Al-Alaq',      'ayat' => 19],
        ['nama' => 'Al-Infitar',   'ayat' => 19],
        ['nama' => 'Al-Balad',     'ayat' => 20],
        // Surah panjang (>20 ayat) → hanya profil mahir
        ['nama' => 'Al-Lail',      'ayat' => 21],
        ['nama' => 'Al-Buruj',     'ayat' => 22],
        ['nama' => 'Al-Insyiqaq',  'ayat' => 25],
        ['nama' => 'Al-Ghasyiyah', 'ayat' => 26],
        ['nama' => 'At-Takwir',    'ayat' => 29],
        ['nama' => 'Al-Fajr',      'ayat' => 30],
        ['nama' => 'Al-Mutaffifin','ayat' => 36],
        ['nama' => 'An-Naba',      'ayat' => 40],
        ['nama' => 'Abasa',        'ayat' => 42],
        ['nama' => 'An-Naziat',    'ayat' => 46],
    ];

    // ── Konfigurasi 3 Profil Kemampuan Siswa ──────────────────────────────
    private array $profil = [
        'mahir' => [
            'jumlah_hafalan_min' => 15,
            'jumlah_hafalan_max' => 25,
            'surah_akses'        => 'semua',       // akses semua 38 surah
            'nilai_min'          => 78,
            'nilai_max'          => 98,
            'label_kelas'        => 'Lulus',
            'catatan'            => [
                'Bacaan sangat baik, makhraj dan fashohah sudah tepat!',
                'Tajwid memuaskan, kelancaran sangat terjaga.',
                'Hafalan lancar dan fasih, terus pertahankan semangat!',
                'Sangat bagus! Coba tambah target surah yang lebih panjang.',
                'Makhraj huruf sudah sangat tepat, kualitas bacaan excellent.',
                'Fashohah sangat baik, tingkatkan ke surah yang lebih panjang.',
                'Prestasi luar biasa! Konsistensi hafalan sangat membanggakan.',
            ],
        ],
        'sedang' => [
            'jumlah_hafalan_min' => 8,
            'jumlah_hafalan_max' => 14,
            'surah_akses'        => 'pendek_sedang', // hanya surah ≤20 ayat
            'nilai_min'          => 60,
            'nilai_max'          => 77,
            'label_kelas'        => 'Lulus',
            'catatan'            => [
                'Bacaan cukup baik, perlu sedikit perbaikan di tajwid.',
                'Fashohah sudah lumayan, latih konsistensi bacaan.',
                'Makhraj perlu diperbaiki di beberapa huruf tertentu.',
                'Sudah cukup bagus, tingkatkan jumlah surah yang dihafal.',
                'Kelancaran baik, fokus perbaikan panjang-pendek (mad).',
                'Perlu latihan lebih pada huruf-huruf tenggorokan.',
                'Ada kemajuan, pertahankan dan tingkatkan kualitasnya.',
            ],
        ],
        'lemah' => [
            'jumlah_hafalan_min' => 2,
            'jumlah_hafalan_max' => 7,
            'surah_akses'        => 'pendek',      // hanya surah ≤7 ayat
            'nilai_min'          => 40,
            'nilai_max'          => 59,
            'label_kelas'        => 'Tidak Lulus',
            'catatan'            => [
                'Masih perlu banyak latihan, hafalan belum begitu lancar.',
                'Makhraj dan fashohah perlu bimbingan lebih intensif.',
                'Bacaan masih terbata-bata, mohon lebih rajin berlatih.',
                'Perlu perbaikan mendasar pada tajwid dan kelancaran bacaan.',
                'Jumlah hafalan masih sangat sedikit, tingkatkan intensitas.',
                'Perlu pendampingan guru secara lebih intensif.',
                'Semangat terus, belajar sedikit demi sedikit setiap hari.',
            ],
        ],
    ];

    // =====================================================================
    //  MAIN: run()
    // =====================================================================
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🚀 DataHafalanMassiveSeeder dimulai...');
        $this->command->info('─────────────────────────────────────────');

        // 1. Ambil semua siswa
        $semuaSiswa = Siswa::with(['guru', 'user'])->get();
        if ($semuaSiswa->isEmpty()) {
            $this->command->error('❌ Tidak ada data siswa. Jalankan UserSeeder terlebih dahulu.');
            return;
        }
        $this->command->info("   Ditemukan {$semuaSiswa->count()} siswa.");

        // 2. Ambil media & model SVM
        $mediaIds = MediaHafalan::where('is_active', true)->pluck('id')->toArray();
        $modelSvm = ModelSvm::where('is_active', true)->first();

        if (empty($mediaIds)) {
            $this->command->error('❌ Tidak ada media hafalan aktif. Jalankan HafalanSeeder terlebih dahulu.');
            return;
        }
        $this->command->info("   Ditemukan " . count($mediaIds) . " media hafalan aktif.");

        // 3. Tentukan profil tiap siswa (distribusi 32% mahir / 37% sedang / 31% lemah)
        $total     = $semuaSiswa->count();
        $jmlMahir  = (int) round($total * 0.32);
        $jmlSedang = (int) round($total * 0.37);
        $jmlLemah  = $total - $jmlMahir - $jmlSedang;

        $this->command->info("   Distribusi profil → Mahir: {$jmlMahir} | Sedang: {$jmlSedang} | Lemah: {$jmlLemah}");
        $this->command->info('');

        // Assign profil ke setiap siswa
        $profilMap = [];
        foreach ($semuaSiswa as $i => $siswa) {
            if ($i < $jmlMahir) {
                $profilMap[$siswa->id] = 'mahir';
            } elseif ($i < $jmlMahir + $jmlSedang) {
                $profilMap[$siswa->id] = 'sedang';
            } else {
                $profilMap[$siswa->id] = 'lemah';
            }
        }

        // 4. Proses tiap siswa
        $totalHafalan  = 0;
        $totalNilai    = 0;
        $totalTraining = 0;
        $counter       = ['mahir' => 0, 'sedang' => 0, 'lemah' => 0];

        $bar = $this->command->getOutput()->createProgressBar($total);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');
        $bar->setMessage('Memulai...');
        $bar->start();

        foreach ($semuaSiswa as $siswa) {
            $bar->setMessage("Siswa #{$siswa->id} ({$profilMap[$siswa->id]})");

            $namaProfilKey = $profilMap[$siswa->id];
            $cfg           = $this->profil[$namaProfilKey];
            $guruId        = $siswa->id_guru;
            $counter[$namaProfilKey]++;

            // Tentukan jumlah hafalan acak sesuai profil
            $jumlahHafalan = rand($cfg['jumlah_hafalan_min'], $cfg['jumlah_hafalan_max']);

            // Pilih pool surah sesuai akses profil
            $pool = $this->getSurahPool($cfg['surah_akses']);
            shuffle($pool);
            $surahDipilih = array_slice($pool, 0, min($jumlahHafalan, count($pool)));

            // Tanggal mulai: antara 6 bulan lalu s/d 1 bulan lalu
            $tanggalMulai = Carbon::now()->subDays(rand(30, 180));

            $nilaiSiswa = [];

            // ── Buat setiap hafalan ──────────────────────────────────
            foreach ($surahDipilih as $idx => $surah) {
                // Tiap hafalan selang 3–7 hari
                $tanggal = (clone $tanggalMulai)->addDays($idx * rand(3, 7));

                $mediaId = $mediaIds[array_rand($mediaIds)];

                $hafalan = DataHafalan::create([
                    'id_siswa'         => $siswa->id,
                    'id_guru'          => $guruId,
                    'id_media'         => $mediaId,
                    'nama_surah'       => $surah['nama'],
                    'jumlah_ayat'      => $surah['ayat'],
                    'periode_semester' => $this->getPeriodeSemester($tanggal),
                    'tanggal_input'    => $tanggal,
                ]);
                $totalHafalan++;

                NilaiEvaluasi::create([
                    'id_hafalan'       => $hafalan->id,
                    'catatan_guru'     => $cfg['catatan'][array_rand($cfg['catatan'])],
                    'tanggal_evaluasi' => (clone $tanggal)->addDay(),
                ]);
                $totalNilai++;
            }

            // ── Auto-generate 1 baris Data Training per siswa ───────
            $totalSurah   = count($surahDipilih);
            $usiaCalc     = $this->hitungUsia($siswa->tanggal_lahir);
            $mediaModusId = $mediaIds[array_rand($mediaIds)];

            // Hapus data training lama untuk siswa ini jika ada (hindari duplikat)
            DataTraining::where('sumber_data', 'LIKE', 'seeder_%')
                ->where('fitur_total_surah', $totalSurah)
                ->where('fitur_usia', $usiaCalc)
                ->delete();

            DataTraining::create([
                'fitur_total_surah' => $totalSurah,
                'fitur_usia'        => $usiaCalc,
                'id_media'          => $mediaModusId,
                'label_kelas'       => $cfg['label_kelas'],
                'sumber_data'       => 'seeder_' . $namaProfilKey,
                'is_valid'          => true,
                'tanggal_input'     => now(),
            ]);
            $totalTraining++;

            $bar->advance();
        }

        $bar->setMessage('Selesai! ✅');
        $bar->finish();

        // 5. Tampilkan ringkasan
        $this->command->info('');
        $this->command->info('');
        $this->command->info('✅ DataHafalanMassiveSeeder selesai!');
        $this->command->info('─────────────────────────────────────────');
        $this->command->table(
            ['Keterangan', 'Jumlah'],
            [
                ['Total Siswa Diproses',          $total],
                ['  ↳ Profil Mahir  (Lulus)',      $counter['mahir']],
                ['  ↳ Profil Sedang (Lulus)',      $counter['sedang']],
                ['  ↳ Profil Lemah  (Tidak Lulus)',$counter['lemah']],
                ['─────────────────────', '──────'],
                ['Total Hafalan Baru Dibuat',      number_format($totalHafalan)],
                ['Total Nilai Evaluasi Dibuat',    number_format($totalNilai)],
                ['Total Data Training Generated',  number_format($totalTraining)],
                ['─────────────────────', '──────'],
                ['Rata-rata Hafalan / Siswa',      round($totalHafalan / max($total, 1), 1)],
            ]
        );
        $this->command->info('');
        $this->command->info('💡 Sekarang buka halaman Perhitungan SVM untuk melihat hasil training!');
    }

    // =====================================================================
    //  HELPER: Pool surah berdasarkan akses profil
    // =====================================================================
    private function getSurahPool(string $akses): array
    {
        return match ($akses) {
            'pendek'        => array_values(array_filter(
                                    $this->surahPool, fn($s) => $s['ayat'] <= 7)),
            'pendek_sedang' => array_values(array_filter(
                                    $this->surahPool, fn($s) => $s['ayat'] <= 20)),
            default         => $this->surahPool,
        };
    }

    // =====================================================================
    //  HELPER: Periode semester dari tanggal
    // =====================================================================
    private function getPeriodeSemester(Carbon $tanggal): string
    {
        $bulan = $tanggal->month;
        $tahun = $tanggal->year;

        // Juli–Desember = Ganjil; Januari–Juni = Genap
        if ($bulan >= 7) {
            return 'Ganjil ' . $tahun . '/' . ($tahun + 1);
        }
        return 'Genap ' . ($tahun - 1) . '/' . $tahun;
    }

    // =====================================================================
    //  HELPER: Hitung usia dari tanggal_lahir (bisa integer tahun atau string)
    // =====================================================================
    private function hitungUsia(mixed $tanggalLahir): int
    {
        if (!$tanggalLahir) return 13;

        // Cast integer (tahun lahir saja)
        if (is_numeric($tanggalLahir)) {
            return max(1, (int) date('Y') - (int) $tanggalLahir);
        }

        // Format date string
        try {
            return max(1, (int) date('Y') - (int) Carbon::parse($tanggalLahir)->year);
        } catch (\Exception $e) {
            return 13;
        }
    }

    // =====================================================================
    //  HELPER: Float acak dengan 2 desimal
    // =====================================================================
    private function randFloat(float $min, float $max): float
    {
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), 2);
    }
}
