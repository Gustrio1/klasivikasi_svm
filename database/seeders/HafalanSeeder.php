<?php

namespace Database\Seeders;

use App\Models\DataHafalan;
use App\Models\DataTraining;
use App\Models\Guru;
use App\Models\HasilKlasifikasi;
use App\Models\LogEvaluasiModel;
use App\Models\MediaHafalan;
use App\Models\ModelSvm;
use App\Models\NilaiEvaluasi;
use App\Models\RekomendasiSiswa;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HafalanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil siswa1 dan guru1
        $siswa1 = Siswa::whereHas('user', fn($q) => $q->where('username', 'siswa1'))->first();
        $guru1  = Guru::whereHas('user', fn($q) => $q->where('username', 'guru1'))->first();

        if (!$siswa1 || !$guru1) {
            $this->command->error('❌ Siswa1 atau Guru1 tidak ditemukan. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // ──────────────────────────────────────────────────────────
        // 1. DATA TRAINING (10 record) — fitur baru: jumlah_ayat, usia, id_media
        // ──────────────────────────────────────────────────────────
        $trainingData = [
            [22, 15, 'Lulus'],
            [20, 16, 'Lulus'],
            [15, 15, 'Lulus'],
            [14, 16, 'Lulus'],
            [13, 17, 'Lulus'],
            [8,  15, 'Tidak Lulus'],
            [9,  16, 'Tidak Lulus'],
            [6,  17, 'Tidak Lulus'],
            [24, 15, 'Lulus'],
            [12, 16, 'Lulus'],
        ];
        foreach ($trainingData as [$jumlahAyat, $usia, $label]) {
            DataTraining::create([
                'fitur_total_surah' => $jumlahAyat,
                'fitur_usia'        => $usia,
                'id_media'          => null,
                'label_kelas'       => $label,
                'sumber_data'       => 'seeder_dummy',
                'is_valid'          => true,
                'tanggal_input'     => now()->subDays(rand(30, 90)),
            ]);
        }
        $this->command->info('  → 10 data training dibuat.');

        // ──────────────────────────────────────────────────────────
        // 2. MODEL SVM (1 record, is_active = true)
        // ──────────────────────────────────────────────────────────
        $model = ModelSvm::create([
            'versi_model'      => 'v1.0.0-dummy',
            'kernel_type'      => 'rbf',
            'parameter_C'      => 1.0,
            'parameter_gamma'  => 0.1,
            'akurasi_model'    => 0.875,
            'is_active'        => true,
            'tanggal_training' => now()->subDays(30),
        ]);
        $this->command->info('  → Model SVM v1.0.0-dummy dibuat (aktif).');

        // ──────────────────────────────────────────────────────────
        // 3. MEDIA HAFALAN (6 record: 3 cetak + 3 digital)
        // ──────────────────────────────────────────────────────────
        $mediaCetak = [
            [
                'nama_media'        => 'Mushaf Tajwid Warna',
                'deskripsi'         => 'Mushaf Al-Qur\'an dengan penanda tajwid berwarna untuk memudahkan pemula.',
                'tips_belajar'      => 'Baca 1 halaman per hari dengan memperhatikan warna tajwid. Ulangi bacaan yang sulit dua kali.',
                'alasan_rekomendasi' => 'Cocok untuk siswa yang masih perlu perbaikan dasar tajwid dan makhraj.',
            ],
            [
                'nama_media'        => 'Buku Panduan Makhraj Huruf',
                'deskripsi'         => 'Panduan lengkap makhraj 28 huruf hijaiyah disertai ilustrasi posisi lidah.',
                'tips_belajar'      => 'Fokus pada huruf ح خ ع غ yang sering salah. Latih di depan cermin setiap hari.',
                'alasan_rekomendasi' => 'Membantu siswa menyempurnakan makhraj huruf.',
            ],
            [
                'nama_media'        => 'Juz Amma Terjemah',
                'deskripsi'         => 'Juz Amma dilengkapi terjemah, asbabun nuzul, dan panduan hafalan per surah.',
                'tips_belajar'      => 'Pahami makna setiap ayat sebelum menghafal. Ini akan memperkuat hafalan jangka panjang.',
                'alasan_rekomendasi' => 'Untuk siswa berprestasi yang ingin memperdalam pemahaman makna hafalan.',
            ],
        ];

        $mediaDigital = [
            [
                'nama_media'        => 'Video Tajwid Pemula (YouTube)',
                'url_link'          => 'https://www.youtube.com/watch?v=example1',
                'deskripsi'         => 'Serial video pembelajaran tajwid dari dasar untuk pemula, total 20 episode.',
                'tips_belajar'      => 'Tonton sambil mengulang bacaan. Aktifkan subtitle untuk membantu pemahaman.',
                'alasan_rekomendasi' => 'Media visual interaktif untuk siswa yang butuh bimbingan tajwid intensif.',
            ],
            [
                'nama_media'        => 'Aplikasi Hafalan Qur\'an (Web)',
                'url_link'          => 'https://quran.com',
                'deskripsi'         => 'Platform web Quran.com dengan fitur audio, terjemah, dan mode hafalan.',
                'tips_belajar'      => 'Gunakan fitur repeat ayat dan aktifkan mode Tafsir untuk pemahaman lebih dalam.',
                'alasan_rekomendasi' => 'Mendukung latihan mandiri dengan umpan balik audio berkualitas tinggi.',
            ],
            [
                'nama_media'        => 'Audio Murottal Syaikh Sudais',
                'url_link'          => 'https://download.quranicaudio.com/example',
                'deskripsi'         => 'Murottal lengkap 30 juz oleh Syaikh Abdurrahman As-Sudais dengan kualitas HD.',
                'tips_belajar'      => 'Dengarkan dan ikuti bacaan beliau. Perhatikan tempo dan nafas pada ayat panjang.',
                'alasan_rekomendasi' => 'Referensi bacaan fasih level tinggi untuk siswa yang sudah menguasai dasar.',
            ],
        ];

        $mediaIds = [];
        foreach ($mediaCetak as $data) {
            $m = MediaHafalan::create(array_merge($data, [
                'jenis_media'  => 'cetak',
                'is_active'    => true,
                'tanggal_input' => now()->subDays(60),
            ]));
            $mediaIds[] = $m->id;
        }
        foreach ($mediaDigital as $data) {
            $m = MediaHafalan::create(array_merge($data, [
                'jenis_media'  => 'digital',
                'is_active'    => true,
                'tanggal_input' => now()->subDays(55),
            ]));
            $mediaIds[] = $m->id;
        }
        // mediaIds: [0]=C cetak, [1]=B cetak, [2]=A cetak, [3]=C digital, [4]=B digital, [5]=A digital
        $this->command->info('  → 6 media hafalan dibuat (3 cetak, 3 digital).');

        // ──────────────────────────────────────────────────────────
        // 4. DATA HAFALAN (8 record untuk siswa1)
        // ──────────────────────────────────────────────────────────
        $hafalanList = [
            ['Al-Fatihah',  7, $mediaIds[5], now()->subDays(90)],
            ['Al-Baqarah',  5, $mediaIds[1], now()->subDays(75)],
            ['Al-Ikhlas',   4, $mediaIds[2], now()->subDays(60)],
            ['Al-Falaq',    5, $mediaIds[4], now()->subDays(50)],
            ['An-Nas',      6, $mediaIds[2], now()->subDays(40)],
            ['Al-Kafirun',  6, $mediaIds[3], now()->subDays(30)],
            ['Al-Kautsar',  3, $mediaIds[5], now()->subDays(20)],
            ['Al-Maun',     7, $mediaIds[1], now()->subDays(10)],
        ];

        $hafalanIds = [];
        foreach ($hafalanList as [$surah, $jmlAyat, $mediaId, $tgl]) {
            $hf = DataHafalan::create([
                'id_siswa'         => $siswa1->id,
                'id_guru'          => $guru1->id,
                'id_media'         => $mediaId,
                'nama_surah'       => $surah,
                'jumlah_ayat'      => $jmlAyat,
                'periode_semester' => \Carbon\Carbon::parse($tgl)->month >= 7
                    ? 'Ganjil ' . \Carbon\Carbon::parse($tgl)->year . '/' . (\Carbon\Carbon::parse($tgl)->year + 1)
                    : 'Genap ' . (\Carbon\Carbon::parse($tgl)->year - 1) . '/' . \Carbon\Carbon::parse($tgl)->year,
                'tanggal_input'    => $tgl,
            ]);
            $hafalanIds[] = $hf->id;
        }
        $this->command->info('  → 8 data hafalan untuk siswa1 dibuat.');

        // ──────────────────────────────────────────────────────────
        // 5. NILAI EVALUASI (8 record)
        // ──────────────────────────────────────────────────────────
        $catatanGuru = [
            'Bacaan sudah bagus, pertahankan konsistensinya!',
            'Perlu latihan huruf ح خ, makhraj masih perlu diperbaiki.',
            'Tajwid perlu diperbaiki di bagian idgham bighunnah.',
            'Fashohah sudah cukup, fokus tingkatkan kelancaran.',
            'Makhraj huruf sudah tepat, tingkatkan kelancaran bacaan.',
            'Bacaan masih terbata-bata, perlu latihan lebih intensif.',
            'Sangat bagus! Terus pertahankan kualitas ini.',
            'Perlu perbaikan pada sifat huruf di beberapa tempat.',
        ];

        foreach ($hafalanList as $idx => [$surah, $jmlAyat, $mediaId, $tgl]) {
            NilaiEvaluasi::create([
                'id_hafalan'      => $hafalanIds[$idx],
                'catatan_guru'    => $catatanGuru[$idx],
                'tanggal_evaluasi' => Carbon::parse($tgl)->addDays(1),
            ]);
        }
        $this->command->info('  → 8 nilai evaluasi dibuat.');

        // ──────────────────────────────────────────────────────────
        // 6. HASIL KLASIFIKASI (8 record)
        // ──────────────────────────────────────────────────────────
        // Mapping kelas berdasarkan nilai rata-rata
        $hasilIds = [];
        foreach ($hafalanList as $idx => [$surah, $jmlAyat, $mediaId, $tgl]) {
            $kelas      = rand(0, 1) ? 'Lulus' : 'Tidak Lulus';
            $confidence = round(rand(75, 97) / 100, 4);
            $semester   = \Carbon\Carbon::parse($tgl)->month >= 7
                ? 'Ganjil ' . \Carbon\Carbon::parse($tgl)->year . '/' . (\Carbon\Carbon::parse($tgl)->year + 1)
                : 'Genap ' . (\Carbon\Carbon::parse($tgl)->year - 1) . '/' . \Carbon\Carbon::parse($tgl)->year;

            $hasil = HasilKlasifikasi::create([
                'id_siswa'            => $siswa1->id,
                'id_model'            => $model->id,
                'periode_semester'    => $semester,
                'total_surah'         => 1,
                'kelas_prediksi'      => $kelas,
                'confidence_score'    => $confidence,
                'media_input'         => json_encode(['id_media' => $mediaId, 'jumlah_ayat' => $jmlAyat]),
                'notifikasi_terkirim' => $idx < 6,
                'tanggal_klasifikasi' => Carbon::parse($tgl)->addDays(2),
                'vector_svm'          => ['jumlah_ayat' => $jmlAyat, 'id_media' => $mediaId],
            ]);
            $hasilIds[$idx] = ['id' => $hasil->id, 'kelas' => $kelas];
        }
        $this->command->info('  → 8 hasil klasifikasi dibuat.');

        $this->command->info('  → Selesai membuat hasil klasifikasi.');

        // ──────────────────────────────────────────────────────────
        // 8. LOG EVALUASI MODEL (1 record)
        // ──────────────────────────────────────────────────────────
        LogEvaluasiModel::create([
            'id_model'         => $model->id,
            'akurasi'          => 0.875,
            'precision'        => 0.863,
            'recall'           => 0.871,
            'f1_score'         => 0.867,
            'confusion_matrix' => [
                'A' => ['A' => 8, 'B' => 1, 'C' => 0],
                'B' => ['A' => 1, 'B' => 7, 'C' => 1],
                'C' => ['A' => 0, 'B' => 2, 'C' => 6],
            ],
            'tanggal_evaluasi' => now()->subDays(25),
        ]);
        $this->command->info('  → 1 log evaluasi model dibuat.');

        $this->command->info('✅ HafalanSeeder selesai! Semua data untuk siswa1 sudah tersedia.');
    }
}
