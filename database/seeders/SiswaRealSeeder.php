<?php

namespace Database\Seeders;

use App\Models\DataHafalan;
use App\Models\DataTraining;
use App\Models\Guru;
use App\Models\MediaHafalan;
use App\Models\ModelSvm;
use App\Models\NilaiEvaluasi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaRealSeeder extends Seeder
{
    // ── 188 Nama Siswa Real dari DATA SISWA.csv ──────────────────────────
    private array $namaSiswa = [
        // Kelompok 1 (baris 3–37)
        'ADELLINA BR BARUS', 'ALPRINA BR SITEPU', 'AMELIA DESWITA',
        'AURA NAZWA AMANAH GINTING', 'AWALINA HAPPYNTA BR TARIGAN',
        'BELLA SYAHRANI', 'DEWI MAULIDINA PUTRI', 'DHEA TASYA ANDIRA',
        'EKRINA DAMAI ULI BR GINTING', 'FADLAN SAHPUTRA SIREGAR',
        'FADLY SOPDYAN GINTING', 'FARA HILLAH', 'HENDA RAYLOKA GINTING',
        'IREN ZASQYA BR SITEPU', 'KASIH LAYASINA BR SURBAKTI',
        'KHAIRANI BR SEMBIRING', 'KHAIRUL HAMZAH SITEPU',
        'LISKA PEBINA BR TARIGAN', 'MAYDINNA KHUMAIRAH',
        'MAYSA FARAS BR MANURUNG', 'MHD. SYAWAL P. PURBA',
        'MUHAMMAD ALFA RIZKI', 'NABIL NABAWI', 'NADILA PUTRI',
        'PAHRI FERDIANSAH PERANGIN-ANGIN', 'PITRA APRILIA', 'PUTRI SARI',
        'REHAN', 'RESMAN DENIS ANDRIANSYAH SIREGAR',
        'RISTI AUDIVA BR SINULINGGA', 'SHABIROH AZ ZAHRA', 'SRI WAHYUNI',
        'SYIFA MAYRIZA BR TARIGAN', 'VIONA OLIVIA BR GINTING', 'WAHYU SUHENDRI',
        // Kelompok 2 (baris 38–73)
        'ALIA RISKINA', 'ANDRA AR RAAFI', 'ARLA AULA PUTRI BR MANURUNG',
        'ARMI BR GINTING', 'AZZAHRA', 'AZZURA NUR AMALIYAH', 'DEA ADINDA',
        'DEVI AMALIA', 'ERLANGGA PRANATA GINTING', 'FADLY MIHAM DANY',
        'FAJAR HARDIANSYAH', 'FAUZAN ADRIANSYAH PRANA SITEPU',
        'FEBRIAN SATRIA', 'FERY IRAWAN', 'IMAM ABIKAROMI', 'IQBAL FIRMANSYAH',
        'KEIYSHA FINZA APRIANI', 'KEYSYA ZIVANNA', 'LEWA LEVISA BR TARIGAN',
        'LIDYA BAGANNIA', 'MHD RAFLI', 'MUNIPA KAHILAH HAFISAH',
        'MUTIARA NABILA YUSRI PILIANG', 'NABILA RIZKY', 'NAZWA ANGGRANI TARIGAN',
        'NUR ISNAINI BR HASIBUAN', 'NUR SALSALINA TARIGAN',
        'NURKAISYAH BR TARIGAN', 'PUTRI', 'RUSMIATI BR SURBAKTI',
        'SALIMA DYAH PRATIWI LUBIS', 'SALSABILA ARASY BR SITEPU',
        'SRI MAY NINTA BR PURBA', 'TIA ULINA BR SEMBIRING',
        'WULAN NURAFIPAH SIMANJORANG', 'ZHAFRAN NADIF',
        // Kelompok 3 (baris 74–106)
        'ADAM WIRANATA', 'ALDO ASRI MALINDO', 'ALYA CITRA LESTARI',
        'ARBAIM ZUHRI RAMADHAN', 'AULIA ZAHRA', 'CLAUDYA WANDIRA',
        'EKA SAMUDERA', 'FADILLA AHMAD GIBRAN', 'FAHRI ANANDSYAH SEMBIRING',
        'FAREL FAHREZA', 'HANI LUTFIA BR. GINTING', 'INAYAH TUSAQDIAH',
        'IQBAL RIZKULLA', 'IRA MIRANA BR TARIGAN', 'KHALIZA AMELIA',
        'LAILAN SUFINA', 'LIDYA CHYNTIA BELLA', 'M. ALFARIZI PRA YUDA',
        'MEISYA AZALIA PUTRI', 'MUHAMAD ARDIANSYAH', 'MUHAMMAD ABIYI',
        'MUHAMMAD FACHRY HAMDANI', 'NABILA ERSYAH', 'NAYSA ROBBAYANA',
        'NOVA FELIZA BR BANGUN', 'RAIVAN FACHREZA', 'RAMDIANSYAH',
        'RASKITA BR PERANGIN-ANGIN', 'REA RAMADHANI', 'RIDHO ANUGRAH GINTING',
        'RONI SYAHPUTRA PASARIBU', 'SYAHRIAN SIREGAR', 'ULVI YATUN JANNA',
        // Kelompok 4 (baris 107–123)
        'AGUSTINA BR PERANGIN-ANGIN', 'ANDINI SAHFITRI', 'ANDRA PRATAMA G',
        'ARIL RIBENTA SINULINGGA', 'BALOIS ZAHRA AYUNDA',
        'CHELSEA MONIKA BR SITOMPUL', 'ERZI AL FAHREIZI',
        'FAHRIYANSA SYAHPUTRA SEMBIRING', 'IHSAN RAIHANSYAH SARAGIH',
        'ILHAM SYAH SUKRI', 'INDAH PERMATA SARI BR SINULINGGA', 'MASITA DEWI',
        'MUHAMMAD ZAHID AZZAKY', 'MUTIA RAZELA BR SINAGA',
        'NAZAR SATYA RAMADHAN', 'NESA HIDAYLA ALFAFA BR GINTING', 'PAKU SADEWO',
        // Kelompok 5 (baris 124–157)
        'AJENG TRISILA BR. KARO', 'ANDIKA ALIF MALANA',
        'AULIA IFFAH AZ-ZAHRA', 'AULIA MAULI BR. S. PELAWI',
        'AULIA NAURA ALA BR GINTING', 'DEA PUTI ASTUTI', 'DINDA NAYSILLA',
        'GHALTISA JAHIRA SHOFA', 'IHDA SYAHRUNI NASUTION',
        'JUWITA OKTAVIA BR BANGUN', 'M. AZMI WAHID BANGUN',
        'M. FAIZUL KHAIRI RANGKUTI', 'M. HIRZAN HARIRI',
        'M. HAPIF IMRON GINTING', 'M. IKHSAN', 'MHD. WENDY', 'MIKA AMELIA',
        'MOH IKHSAN SITOPU', 'MUHAMMAD FAQIRY AULIA',
        'MUHAMMAD SYAFULLAH SEMBIRING', 'NAZA ARYAN', 'NURIL ZAKIA BR SEMBIRING',
        'NURILL RIZI ARINI', 'PUTRI CHAIRANI', 'RAIHAN AFLAH PRATAMA MANDA',
        'RANISKI GURUSINGA', 'RISKI SARTIKA', 'SELLA BR TUMANGGOR',
        'SYARAH LUTHFI AZKIA DAULAY', 'SYIFA AZURA MUMTAZAH', 'TASYA JESIKA',
        'YAJENG ANGGUN MAISAROH', 'ZAHRA MAYLANI', 'ZAIDAN JASINDA GINTING',
        // Kelompok 6 (baris 158–190)
        'ADITYA NABIL GINTING SUKA', 'AGUNG APRIADI', 'ALIEF ALFIANSYAH',
        'ANDIKA YUDHISTIRA', 'ANGGA SHAKA SURBAKTI', 'ANISA MAGHFIRA GINTING',
        'BAGAS SYAHREZA', 'DARAKANTA BR SEMBIRING', 'DIGA CEPY BR MILALA',
        'DINARA SAFINA BR GINTING', 'DWI MARSELLA BR PURBA',
        'FAHMI QORDI BANCIN', 'FIKRI HARIANSYAH', 'FITRI ANNISA BR SITEPU',
        'GISEL JENETA ANDREA BR SINUHAJI', 'HAZIZAH RAHMADANI',
        'ILHAM APRIANSYAH SITEPU', 'KAYLA AZZURA AMRY',
        'MARTUA ZIMLI HAFIZ NASUTION', 'MARVELLINO UNTORO',
        'MOUZZA RAMADHINY', 'NABILA RAHMA OKTAVIA',
        'NINA FADHANY NOLA BR LINGGA', 'NUGRAHA ARKANANTA GINTING',
        'NUR FICILMI KAFFAH', 'NURUL ARIANI',
        'RADOT ARIS RIFALDI SIMATUPANG', 'RAYSHA TRINABILA',
        'REFI REISYA BR SITEPU', 'SABELLA', 'SELVI TISA MAHARANI',
        'SYAHIRA NANDITA', 'YUNI WULANDANI BR SINULINGGA',
    ];

    // ── 38 Surah Juz Amma ────────────────────────────────────────────────
    private array $surahPool = [
        ['nama' => 'Al-Asr',        'ayat' => 3],
        ['nama' => 'Al-Kautsar',    'ayat' => 3],
        ['nama' => 'An-Nasr',       'ayat' => 3],
        ['nama' => 'Al-Ikhlas',     'ayat' => 4],
        ['nama' => 'Quraisy',       'ayat' => 4],
        ['nama' => 'Al-Falaq',      'ayat' => 5],
        ['nama' => 'Al-Fil',        'ayat' => 5],
        ['nama' => 'Al-Masad',      'ayat' => 5],
        ['nama' => 'An-Nas',        'ayat' => 6],
        ['nama' => 'Al-Kafirun',    'ayat' => 6],
        ['nama' => 'Al-Fatihah',    'ayat' => 7],
        ['nama' => 'Al-Maun',       'ayat' => 7],
        ['nama' => 'Az-Zalzalah',   'ayat' => 8],
        ['nama' => 'Al-Bayyinah',   'ayat' => 8],
        ['nama' => 'Al-Humazah',    'ayat' => 9],
        ['nama' => 'At-Takatsur',   'ayat' => 8],
        ['nama' => 'Al-Insyirah',   'ayat' => 8],
        ['nama' => 'At-Tin',        'ayat' => 8],
        ['nama' => 'Ad-Duha',       'ayat' => 11],
        ['nama' => 'Al-Qariah',     'ayat' => 11],
        ['nama' => 'Al-Adiyat',     'ayat' => 11],
        ['nama' => 'Al-Qadr',       'ayat' => 5],
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
        ['nama' => 'Al-Mutaffifin', 'ayat' => 36],
        ['nama' => 'An-Naba',       'ayat' => 40],
        ['nama' => 'Abasa',         'ayat' => 42],
        ['nama' => 'An-Naziat',     'ayat' => 46],
    ];

    // ── Konfigurasi profil kemampuan ─────────────────────────────────────
    private array $profil = [
        'mahir' => [
            'hfl_min' => 15, 'hfl_max' => 25,
            'surah'   => 'semua',
            'nil_min' => 78, 'nil_max' => 98,
            'label'   => 'Lulus',
            'catatan' => [
                'Bacaan sangat baik, makhraj dan fashohah sudah tepat!',
                'Tajwid memuaskan, kelancaran sangat terjaga.',
                'Hafalan lancar dan fasih, terus pertahankan semangat!',
                'Sangat bagus! Coba tambah target surah yang lebih panjang.',
                'Makhraj huruf sudah sangat tepat, kualitas bacaan excellent.',
                'Prestasi luar biasa! Konsistensi hafalan sangat membanggakan.',
            ],
        ],
        'sedang' => [
            'hfl_min' => 8, 'hfl_max' => 14,
            'surah'   => 'pendek_sedang',
            'nil_min' => 60, 'nil_max' => 77,
            'label'   => 'Lulus',
            'catatan' => [
                'Bacaan cukup baik, perlu sedikit perbaikan di tajwid.',
                'Fashohah sudah lumayan, latih konsistensi bacaan.',
                'Makhraj perlu diperbaiki di beberapa huruf.',
                'Sudah cukup bagus, tingkatkan jumlah surah hafalan.',
                'Kelancaran baik, fokus perbaikan panjang-pendek (mad).',
                'Ada kemajuan, pertahankan dan tingkatkan kualitasnya.',
            ],
        ],
        'lemah' => [
            'hfl_min' => 2, 'hfl_max' => 7,
            'surah'   => 'pendek',
            'nil_min' => 40, 'nil_max' => 59,
            'label'   => 'Tidak Lulus',
            'catatan' => [
                'Masih perlu banyak latihan, hafalan belum begitu lancar.',
                'Makhraj dan fashohah perlu bimbingan lebih intensif.',
                'Bacaan masih terbata-bata, mohon lebih rajin berlatih.',
                'Perlu perbaikan mendasar pada tajwid dan kelancaran.',
                'Jumlah hafalan masih sedikit, tingkatkan intensitas belajar.',
                'Perlu pendampingan guru secara lebih intensif.',
            ],
        ],
    ];

    // =====================================================================
    //  MAIN
    // =====================================================================
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🚀 SiswaRealSeeder — 188 siswa dari DATA SISWA.csv');
        $this->command->info('══════════════════════════════════════════════════');

        // ── Ambil atau buat Guru (3 guru) ────────────────────────────────
        $guruIds = $this->pastikanGuru();

        // ── Ambil Media & Model SVM aktif ────────────────────────────────
        $mediaIds = MediaHafalan::where('is_active', true)->pluck('id')->toArray();
        $modelSvm = ModelSvm::where('is_active', true)->first();

        if (empty($mediaIds)) {
            $this->command->error('❌ Tidak ada media hafalan. Jalankan HafalanSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('   ✔ Guru tersedia   : ' . count($guruIds));
        $this->command->info('   ✔ Media tersedia  : ' . count($mediaIds));
        $this->command->info('   ✔ Total siswa CSV : ' . count($this->namaSiswa));
        $this->command->info('');

        // ── Distribusi profil: 32% mahir / 37% sedang / 31% lemah ────────
        $total     = count($this->namaSiswa);
        $jmlMahir  = (int) round($total * 0.32); // ~60
        $jmlSedang = (int) round($total * 0.37); // ~70
        $jmlLemah  = $total - $jmlMahir - $jmlSedang; // ~58

        $this->command->info("   Distribusi → Mahir: {$jmlMahir} | Sedang: {$jmlSedang} | Lemah: {$jmlLemah}");
        $this->command->info('');

        // ── Counter ───────────────────────────────────────────────────────
        $ctrSiswa     = 0;
        $ctrHafalan   = 0;
        $ctrNilai     = 0;
        $ctrTraining  = 0;
        $ctrSkip      = 0;
        $profilCount  = ['mahir' => 0, 'sedang' => 0, 'lemah' => 0];

        $bar = $this->command->getOutput()->createProgressBar($total);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');
        $bar->setMessage('Memulai...');
        $bar->start();

        foreach ($this->namaSiswa as $idx => $nama) {
            // Tentukan profil berdasarkan urutan index
            if ($idx < $jmlMahir) {
                $namaProfilKey = 'mahir';
            } elseif ($idx < $jmlMahir + $jmlSedang) {
                $namaProfilKey = 'sedang';
            } else {
                $namaProfilKey = 'lemah';
            }
            $cfg = $this->profil[$namaProfilKey];
            $profilCount[$namaProfilKey]++;

            $bar->setMessage("#{$idx} {$nama} [{$namaProfilKey}]");

            // ── Buat User ─────────────────────────────────────────────
            $username = $this->buatUsername($nama, $idx + 1);

            // Skip jika user sudah ada (idempotent)
            if (User::where('username', $username)->exists()) {
                $ctrSkip++;
                $bar->advance();
                continue;
            }

            // Tentukan jenis kelamin dari nama (heuristik sederhana)
            $jenisKelamin = $this->tebakJenisKelamin($nama);

            // Tahun lahir: 17–18 tahun lalu
            $tahunLahir = (int) date('Y') - rand(17, 18);

            // Guru bergantian (round-robin per 63 siswa)
            $guruId = $guruIds[$idx % count($guruIds)];

            // Kelas (VII, VIII, IX berurut tiap ~63 siswa)
            $kelasRomawi = ['VII', 'VIII', 'IX'][(int) floor($idx / 63)];
            $kelasHuruf  = chr(65 + ($idx % 4)); // A, B, C, D
            $kelas       = $kelasRomawi . '-' . $kelasHuruf;

            $user = User::create([
                'username'     => $username,
                'password'     => Hash::make('password'),
                'role'         => 'siswa',
                'nama_lengkap' => ucwords(strtolower($nama)),
                'email'        => null,
                'is_active'    => true,
            ]);

            $siswa = Siswa::create([
                'id_user'       => $user->id,
                'id_guru'       => $guruId,
                'nisn'          => $this->buatNisn($idx),
                'kelas'         => $kelas,
                'jenis_kelamin' => $jenisKelamin,
                'tanggal_lahir' => $tahunLahir,
            ]);
            $ctrSiswa++;

            // ── Buat Data Hafalan ─────────────────────────────────────
            $jumlahHafalan = rand($cfg['hfl_min'], $cfg['hfl_max']);
            $pool          = $this->getSurahPool($cfg['surah']);
            shuffle($pool);
            $surahDipilih  = array_slice($pool, 0, min($jumlahHafalan, count($pool)));

            $tanggalMulai = Carbon::now()->subDays(rand(30, 180));
            $nilaiSiswa   = [];

            foreach ($surahDipilih as $i => $surah) {
                $tanggal = (clone $tanggalMulai)->addDays($i * rand(3, 7));
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
                $ctrHafalan++;

                // Nilai Evaluasi
                NilaiEvaluasi::create([
                    'id_hafalan'       => $hafalan->id,
                    'catatan_guru'     => $cfg['catatan'][array_rand($cfg['catatan'])],
                    'tanggal_evaluasi' => (clone $tanggal)->addDay(),
                ]);
                $ctrNilai++;
            }

            // ── Data Training (1 baris per siswa) ────────────────────
            DataTraining::create([
                'fitur_total_surah' => count($surahDipilih),
                'fitur_usia'        => (int) date('Y') - $tahunLahir,
                'id_media'          => $mediaIds[array_rand($mediaIds)],
                'label_kelas'       => $cfg['label'],
                'sumber_data'       => 'csv_real_' . $namaProfilKey,
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
        $this->command->info('══════════════════════════════════════════════════');
        $this->command->table(
            ['Keterangan', 'Jumlah'],
            [
                ['Siswa dari CSV berhasil dibuat',    $ctrSiswa],
                ['Siswa dilewati (sudah ada)',         $ctrSkip],
                ['  ↳ Profil Mahir  (Lulus)',          $profilCount['mahir']],
                ['  ↳ Profil Sedang (Lulus)',          $profilCount['sedang']],
                ['  ↳ Profil Lemah  (Tidak Lulus)',    $profilCount['lemah']],
                ['─────────────────────────────', '──────'],
                ['Total Hafalan Dibuat',               number_format($ctrHafalan)],
                ['Total Nilai Evaluasi Dibuat',        number_format($ctrNilai)],
                ['Total Data Training Dibuat',         number_format($ctrTraining)],
                ['─────────────────────────────', '──────'],
                ['Rata-rata Hafalan / Siswa',          $ctrSiswa > 0 ? round($ctrHafalan / $ctrSiswa, 1) : 0],
            ]
        );
        $this->command->info('');
        $this->command->info('💡 Login siswa: username=siswa_001, password=password');
        $this->command->info('💡 Buka halaman Perhitungan SVM untuk melihat hasil training!');
    }

    // =====================================================================
    //  HELPERS
    // =====================================================================

    /** Pastikan 3 guru tersedia, buat jika belum ada */
    private function pastikanGuru(): array
    {
        $guruData = [
            ['username' => 'guru1', 'nama' => 'Ustadz Ahmad Fauzi',    'nip' => '198501010001'],
            ['username' => 'guru2', 'nama' => 'Ustadzah Siti Aminah',  'nip' => '199002150002'],
            ['username' => 'guru3', 'nama' => 'Ustadz Hasan Basri',    'nip' => '198803200003'],
            ['username' => 'guru4', 'nama' => 'Ustadzah Rahmah Dewi',  'nip' => '199507110004'],
            ['username' => 'guru5', 'nama' => 'Ustadz Faisal Lubis',   'nip' => '198612280005'],
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
                    'no_telp'        => '08123456' . str_pad(count($ids)+1, 4, '0', STR_PAD_LEFT),
                    'is_active'      => true,
                ]
            );
            $ids[] = $guru->id;
        }
        return $ids;
    }

    /** Buat username unik dari nama siswa */
    private function buatUsername(string $nama, int $urut): string
    {
        return 'siswa_' . str_pad($urut, 3, '0', STR_PAD_LEFT);
    }

    /** Buat NISN unik 10 digit */
    private function buatNisn(int $idx): string
    {
        return str_pad(1000000000 + $idx, 10, '0', STR_PAD_LEFT);
    }

    /** Tebak jenis kelamin dari kata kunci nama */
    private function tebakJenisKelamin(string $nama): string
    {
        $nama = strtoupper($nama);
        $kataPria = ['MHD', 'MUHAMMAD', 'MUHAMAD', 'FADLAN', 'FADLY', 'REHAN',
                     'NABIL', 'PAHRI', 'WAHYU', 'RESMAN', 'ANDRA', 'ERLANGGA',
                     'FAJAR', 'FAUZAN', 'FEBRIAN', 'FERY', 'IMAM', 'IQBAL',
                     'MHD', 'ZHAFRAN', 'ADAM', 'ALDO', 'ARBAIM', 'EKA', 'FADILLA',
                     'FAHRI', 'FAREL', 'M.', 'RAIVAN', 'RAMDIANSYAH', 'RIDHO',
                     'RONI', 'SYAHRIAN', 'ANDIKA', 'ANGGA', 'BAGAS', 'ADITYA',
                     'AGUNG', 'ALIEF', 'ERZI', 'FAHRIYANSA', 'IHSAN', 'ILHAM',
                     'MUHAMMAD', 'NAZAR', 'PAKU', 'NUGRAHA', 'RADOT', 'FIKRI',
                     'FAHMI', 'MARTUA', 'MARVELLINO', 'RAIHAN', 'RANISKI', 'ANDRA'];
        foreach ($kataPria as $kata) {
            if (str_starts_with($nama, $kata)) return 'L';
        }
        return 'P';
    }

    /** Filter surah berdasarkan profil akses */
    private function getSurahPool(string $akses): array
    {
        return match ($akses) {
            'pendek'        => array_values(array_filter($this->surahPool, fn($s) => $s['ayat'] <= 7)),
            'pendek_sedang' => array_values(array_filter($this->surahPool, fn($s) => $s['ayat'] <= 20)),
            default         => $this->surahPool,
        };
    }

    /** Periode semester dari tanggal */
    private function getPeriodeSemester(Carbon $tanggal): string
    {
        $b = $tanggal->month;
        $t = $tanggal->year;
        return $b >= 7
            ? 'Ganjil ' . $t . '/' . ($t + 1)
            : 'Genap ' . ($t - 1) . '/' . $t;
    }

    /** Float acak 2 desimal */
    private function randFloat(float $min, float $max): float
    {
        return round($min + mt_rand() / mt_getrandmax() * ($max - $min), 2);
    }
}
