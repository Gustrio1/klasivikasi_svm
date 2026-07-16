<?php

namespace Database\Seeders;

use App\Models\DataHafalan;
use App\Models\DataTraining;
use App\Models\MediaHafalan;
use App\Models\NilaiEvaluasi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class SiswaRealSeeder extends Seeder
{
    // ── Surah Juz Amma (untuk mapping jumlah ayat → nama surah) ──────────
    private array $surahPool = [
        ['nama' => 'Al-Asr',        'ayat' => 3],
        ['nama' => 'Al-Kautsar',    'ayat' => 3],
        ['nama' => 'An-Nasr',       'ayat' => 3],
        ['nama' => 'Al-Ikhlas',     'ayat' => 4],
        ['nama' => 'Quraisy',       'ayat' => 4],
        ['nama' => 'Al-Falaq',      'ayat' => 5],
        ['nama' => 'Al-Fil',        'ayat' => 5],
        ['nama' => 'Al-Masad',      'ayat' => 5],
        ['nama' => 'Al-Qadr',       'ayat' => 5],
        ['nama' => 'An-Nas',        'ayat' => 6],
        ['nama' => 'Al-Kafirun',    'ayat' => 6],
        ['nama' => 'Al-Fatihah',    'ayat' => 7],
        ['nama' => 'Al-Maun',       'ayat' => 7],
        ['nama' => 'Az-Zalzalah',   'ayat' => 8],
        ['nama' => 'Al-Bayyinah',   'ayat' => 8],
        ['nama' => 'At-Takatsur',   'ayat' => 8],
        ['nama' => 'Al-Insyirah',   'ayat' => 8],
        ['nama' => 'At-Tin',        'ayat' => 8],
        ['nama' => 'Al-Humazah',    'ayat' => 9],
        ['nama' => 'Ad-Duha',       'ayat' => 11],
        ['nama' => 'Al-Qariah',     'ayat' => 11],
        ['nama' => 'Al-Adiyat',     'ayat' => 11],
        ['nama' => 'Al-Zalzalah',   'ayat' => 8],
        ['nama' => 'Asy-Syams',     'ayat' => 15],
        ['nama' => 'At-Tariq',      'ayat' => 17],
        ['nama' => 'Al-Ala',        'ayat' => 19],
        ['nama' => 'Al-Alaq',       'ayat' => 19],
        ['nama' => 'Al-Infitar',    'ayat' => 19],
        ['nama' => 'Al-Balad',      'ayat' => 20],
        ['nama' => 'Al-Lail',       'ayat' => 21],
        ['nama' => 'Al-Buruj',      'ayat' => 22],
        ['nama' => 'Al-Insyiqaq',   'ayat' => 25],
        ['nama' => 'Al-Ghasyiyah',  'ayat' => 26],
        ['nama' => 'At-Takwir',     'ayat' => 29],
        ['nama' => 'Al-Fajr',       'ayat' => 30],
    ];

    // ── Catatan evaluasi otomatis ─────────────────────────────────────────
    private array $catatanTinggi = [
        'Hafalan lancar dan fasih, terus pertahankan semangat!',
        'Bacaan sangat baik, makhraj sudah tepat!',
        'Sangat bagus! Tajwid memuaskan, kelancaran terjaga.',
        'Prestasi luar biasa! Konsistensi hafalan sangat membanggakan.',
        'Makhraj huruf sudah sangat tepat, kualitas bacaan excellent.',
    ];

    private array $catatanSedang = [
        'Bacaan cukup baik, perlu sedikit perbaikan di tajwid.',
        'Sudah cukup bagus, tingkatkan jumlah surah hafalan.',
        'Kelancaran baik, fokus perbaikan panjang-pendek (mad).',
        'Ada kemajuan, pertahankan dan tingkatkan kualitasnya.',
        'Fashohah sudah lumayan, latih konsistensi bacaan.',
    ];

    private array $catatanRendah = [
        'Masih perlu banyak latihan, hafalan belum begitu lancar.',
        'Bacaan masih terbata-bata, mohon lebih rajin berlatih.',
        'Perlu perbaikan mendasar pada tajwid dan kelancaran.',
        'Perlu pendampingan guru secara lebih intensif.',
        'Jumlah hafalan masih sedikit, tingkatkan intensitas belajar.',
    ];

    // =====================================================================
    //  MAIN
    // =====================================================================
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🚀 SiswaRealSeeder — 35 siswa real dari data_siswa_fix.csv');
        $this->command->info('════════════════════════════════════════════════════════════');

        // ── 1. Pastikan Guru tersedia ─────────────────────────────────────
        $guruIds = $this->pastikanGuru();
        $guruId  = $guruIds[0]; // Guru utama (guru1)

        // ── 2. Pastikan Media Hafalan tersedia ────────────────────────────
        [$mediaCetakId, $mediaDigitalId] = $this->pastikanMedia();

        // ── 3. Baca & parse CSV ───────────────────────────────────────────
        $csvPath = base_path('data_siswa_fix.csv');

        if (!file_exists($csvPath)) {
            $this->command->error("❌ File tidak ditemukan: {$csvPath}");
            return;
        }

        $siswaData = $this->parseCSV($csvPath);

        $this->command->info('   ✔ Guru tersedia   : ' . count($guruIds));
        $this->command->info('   ✔ Media Cetak ID  : ' . $mediaCetakId);
        $this->command->info('   ✔ Media Digital ID: ' . $mediaDigitalId);
        $this->command->info('   ✔ Total siswa CSV : ' . count($siswaData));
        $this->command->info('');

        // ── 4. Counter ────────────────────────────────────────────────────
        $ctrSiswa    = 0;
        $ctrHafalan  = 0;
        $ctrNilai    = 0;
        $ctrTraining = 0;
        $ctrSkip     = 0;
        $labelCount  = ['Lulus' => 0, 'Tidak Lulus' => 0];

        $bar = $this->command->getOutput()->createProgressBar(count($siswaData));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');
        $bar->setMessage('Memulai...');
        $bar->start();

        // ── 5. Loop setiap siswa ──────────────────────────────────────────
        foreach ($siswaData as $idx => $data) {
            $nama     = $data['nama'];
            $hafalan  = $data['hafalan']; // array of [urut, ayat, media]
            $urut     = $idx + 1;
            $username = 'siswa_' . str_pad($urut, 3, '0', STR_PAD_LEFT);

            $bar->setMessage("#{$urut} {$nama}");

            // Skip jika user sudah ada (idempotent)
            if (User::where('username', $username)->exists()) {
                $ctrSkip++;
                $bar->advance();
                continue;
            }

            // ── Buat User ─────────────────────────────────────────────────
            $jenisKelamin = $this->tebakJenisKelamin($nama);
            $tahunLahir   = (int) date('Y') - rand(15, 17);

            // Rotasi guru (round-robin)
            $guruIdSiswa = $guruIds[$idx % count($guruIds)];

            // Kelas: semua di kelas IX (sesuai konteks data real)
            $kelasHuruf = chr(65 + ($idx % 4)); // A, B, C, D
            $kelas      = 'IX-' . $kelasHuruf;

            $user = User::create([
                'username'     => $username,
                'password'     => Hash::make('password'),
                'role'         => 'siswa',
                'nama_lengkap' => $nama,
                'email'        => null,
                'is_active'    => true,
            ]);

            $siswa = Siswa::create([
                'id_user'       => $user->id,
                'id_guru'       => $guruIdSiswa,
                'nisn'          => str_pad(1000000000 + $idx, 10, '0', STR_PAD_LEFT),
                'kelas'         => $kelas,
                'jenis_kelamin' => $jenisKelamin,
                'tanggal_lahir' => $tahunLahir,
            ]);
            $ctrSiswa++;

            // ── Buat Data Hafalan (50 pertemuan per siswa) ────────────────
            $totalAyat   = 0;
            $cetakCount  = 0;
            $digitalCount = 0;

            // Tanggal mulai: 350 hari yang lalu (50 pertemuan × 7 hari)
            $tanggalMulai = Carbon::now()->subDays(350);

            foreach ($hafalan as $i => $h) {
                $ayat    = $h['ayat'];
                $mediaCsv= $h['media'];  // "Media Cetak" atau "Media Aplikasi"
                $tanggal = (clone $tanggalMulai)->addDays($i * 7);

                // Mapping media CSV → id_media di database
                if (str_contains(strtolower($mediaCsv), 'aplikasi')) {
                    $mediaId = $mediaDigitalId;
                    $digitalCount++;
                } else {
                    $mediaId = $mediaCetakId;
                    $cetakCount++;
                }

                // Cari nama surah berdasarkan jumlah ayat terdekat
                $namaSurah = $this->cariSurah($ayat);

                $hafRecord = DataHafalan::create([
                    'id_siswa'         => $siswa->id,
                    'id_guru'          => $guruIdSiswa,
                    'id_media'         => $mediaId,
                    'nama_surah'       => $namaSurah,
                    'jumlah_ayat'      => $ayat,
                    'periode_semester' => $this->getPeriodeSemester($tanggal),
                    'tanggal_input'    => $tanggal,
                ]);
                $ctrHafalan++;

                // ── Nilai Evaluasi (1 per hafalan) ────────────────────────
                $catatan = $this->getCatatan($ayat);
                NilaiEvaluasi::create([
                    'id_hafalan'       => $hafRecord->id,
                    'catatan_guru'     => $catatan,
                    'tanggal_evaluasi' => (clone $tanggal)->addDay(),
                ]);
                $ctrNilai++;

                $totalAyat += $ayat;
            }

            // ── Data Training (1 record agregat per siswa) ────────────────
            $rataAyat   = count($hafalan) > 0 ? $totalAyat / count($hafalan) : 0;
            $label      = $this->tentukanLabel($rataAyat);
            $labelCount[$label]++;

            // Media yang paling sering dipakai
            $mediaTraining = $cetakCount >= $digitalCount ? $mediaCetakId : $mediaDigitalId;

            DataTraining::create([
                'fitur_total_surah' => (int) round($rataAyat),
                'fitur_usia'        => (int) date('Y') - $tahunLahir,
                'id_media'          => $mediaTraining,
                'label_kelas'       => $label,
                'sumber_data'       => 'csv_real_' . strtolower($label),
                'is_valid'          => true,
                'tanggal_input'     => now(),
            ]);
            $ctrTraining++;

            $bar->advance();
        }

        $bar->setMessage('Selesai! ✅');
        $bar->finish();

        // ── Ringkasan ─────────────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('');
        $this->command->info('✅ SiswaRealSeeder selesai!');
        $this->command->info('════════════════════════════════════════════════════════════');
        $this->command->table(
            ['Keterangan', 'Jumlah'],
            [
                ['Siswa dari CSV berhasil dibuat',    $ctrSiswa],
                ['Siswa dilewati (sudah ada)',         $ctrSkip],
                ['  ↳ Label Lulus',                   $labelCount['Lulus']],
                ['  ↳ Label Tidak Lulus',             $labelCount['Tidak Lulus']],
                ['──────────────────────────────',   '──────'],
                ['Total Hafalan Dibuat',              number_format($ctrHafalan)],
                ['Total Nilai Evaluasi Dibuat',       number_format($ctrNilai)],
                ['Total Data Training Dibuat',        number_format($ctrTraining)],
                ['──────────────────────────────',   '──────'],
                ['Rata-rata Hafalan / Siswa',         $ctrSiswa > 0 ? round($ctrHafalan / $ctrSiswa, 1) : 0],
            ]
        );
        $this->command->info('');
        $this->command->info('💡 Login siswa: username=siswa_001, password=password');
    }

    // =====================================================================
    //  HELPERS
    // =====================================================================

    /**
     * Parse file CSV dan kembalikan array siswa beserta data hafalan.
     * Format CSV: NO, urutan_pertemuan, Nama Siswa, Jumlah Ayat, Media
     * Nama siswa hanya ada di baris pertama setiap kelompok (forward-fill).
     */
    private function parseCSV(string $path): array
    {
        $siswaData    = [];
        $currentNama  = null;
        $rowNum       = 0;

        $handle = fopen($path, 'r');
        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            // Skip baris header (baris 1) dan baris kosong (baris 2)
            if ($rowNum <= 2) continue;

            $no      = isset($row[0]) ? trim($row[0]) : '';
            $nama    = isset($row[2]) ? trim($row[2]) : '';
            $ayat    = isset($row[3]) ? (int) trim($row[3]) : 0;
            $media   = isset($row[4]) ? trim($row[4]) : '';

            // Baris baru siswa: kolom NO tidak kosong dan ada nama
            if ($no !== '' && $nama !== '') {
                $currentNama = $nama;
                $siswaData[] = [
                    'nama'    => $currentNama,
                    'hafalan' => [],
                ];
            }

            // Tambahkan data hafalan ke siswa saat ini
            if ($currentNama !== null && $ayat > 0 && $media !== '') {
                $lastIdx = count($siswaData) - 1;
                $siswaData[$lastIdx]['hafalan'][] = [
                    'ayat'  => $ayat,
                    'media' => $media,
                ];
            }
        }
        fclose($handle);

        return $siswaData;
    }

    /** Pastikan 5 guru tersedia di database */
    private function pastikanGuru(): array
    {
        $guruData = [
            ['username' => 'guru1', 'nama' => 'Ustadz Ahmad Fauzi',   'nip' => '198501010001'],
            ['username' => 'guru2', 'nama' => 'Ustadzah Siti Aminah', 'nip' => '199002150002'],
            ['username' => 'guru3', 'nama' => 'Ustadz Hasan Basri',   'nip' => '198803200003'],
            ['username' => 'guru4', 'nama' => 'Ustadzah Rahmah Dewi', 'nip' => '199507110004'],
            ['username' => 'guru5', 'nama' => 'Ustadz Faisal Lubis',  'nip' => '198612280005'],
        ];

        $ids = [];
        foreach ($guruData as $g) {
            $user = User::firstOrCreate(
                ['username' => $g['username']],
                [
                    'password'     => Hash::make('password'),
                    'role'         => 'guru',
                    'nama_lengkap' => $g['nama'],
                    'email'        => null,
                    'is_active'    => true,
                ]
            );
            $guru = \App\Models\Guru::firstOrCreate(
                ['id_user' => $user->id],
                [
                    'nip'            => $g['nip'],
                    'mata_pelajaran' => 'Tahfidz Al-Qur\'an',
                    'no_telp'        => '08123456' . str_pad(count($ids) + 1, 4, '0', STR_PAD_LEFT),
                    'is_active'      => true,
                ]
            );
            $ids[] = $guru->id;
        }
        return $ids;
    }

    /**
     * Pastikan minimal 1 media cetak dan 1 media digital tersedia.
     * Kembalikan [id_cetak, id_digital].
     */
    private function pastikanMedia(): array
    {
        $mediaCetak = \App\Models\MediaHafalan::where('jenis_media', 'cetak')
            ->where('is_active', true)
            ->first();

        $mediaDigital = \App\Models\MediaHafalan::where('jenis_media', 'digital')
            ->where('is_active', true)
            ->first();

        if (!$mediaCetak) {
            $mediaCetak = \App\Models\MediaHafalan::create([
                'nama_media'   => 'Mushaf Al-Qur\'an',
                'jenis_media'  => 'cetak',
                'is_active'    => true,
            ]);
        }

        if (!$mediaDigital) {
            $mediaDigital = \App\Models\MediaHafalan::create([
                'nama_media'   => 'Aplikasi Al-Qur\'an',
                'jenis_media'  => 'digital',
                'is_active'    => true,
            ]);
        }

        return [$mediaCetak->id, $mediaDigital->id];
    }

    /**
     * Cari nama surah berdasarkan jumlah ayat terdekat dari surahPool.
     * Dipakai karena CSV tidak mencantumkan nama surah, hanya jumlah ayat.
     */
    private function cariSurah(int $jumlahAyat): string
    {
        $closest = 'Al-Fatihah';
        $minDiff = PHP_INT_MAX;

        foreach ($this->surahPool as $surah) {
            $diff = abs($surah['ayat'] - $jumlahAyat);
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $closest = $surah['nama'];
            }
        }
        return $closest;
    }

    /**
     * Tentukan label SVM berdasarkan rata-rata ayat per pertemuan:
     * A = Mahir (rata-rata ≥ 18), B = Sedang (12–17), C = Lemah (< 12)
     */
    private function tentukanLabel(float $rataAyat): string
    {
        $label = $rataAyat >= 12 ? 'Lulus' : 'Tidak Lulus';
        return $label;
    }

    /**
     * Pilih catatan evaluasi berdasarkan jumlah ayat (proxy kualitas hafalan).
     */
    private function getCatatan(int $ayat): string
    {
        if ($ayat >= 18) {
            return $this->catatanTinggi[array_rand($this->catatanTinggi)];
        } elseif ($ayat >= 12) {
            return $this->catatanSedang[array_rand($this->catatanSedang)];
        }
        return $this->catatanRendah[array_rand($this->catatanRendah)];
    }

    /**
     * Tebak jenis kelamin dari kata awal nama (heuristik sederhana).
     */
    private function tebakJenisKelamin(string $nama): string
    {
        $nama = strtoupper($nama);
        $kataPria = [
            'MUHAMMAD', 'MUHAMAD', 'MOH', 'MHD', 'ADITYA', 'AGUNG',
            'BAGAS', 'DIGA', 'FIKRI', 'ILHAM', 'MARVELLINO', 'ANDIKA',
            'AULIA MAJID', // Aulia bisa laki-laki jika ada kata Majid
        ];
        foreach ($kataPria as $kata) {
            if (str_starts_with($nama, $kata)) return 'L';
        }
        return 'P';
    }

    /** Hitung periode semester dari tanggal hafalan */
    private function getPeriodeSemester(Carbon $tanggal): string
    {
        $bulan = $tanggal->month;
        $tahun = $tanggal->year;
        return $bulan >= 7
            ? 'Ganjil ' . $tahun . '/' . ($tahun + 1)
            : 'Genap ' . ($tahun - 1) . '/' . $tahun;
    }
}
