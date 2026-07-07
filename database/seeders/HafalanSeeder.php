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
        // 1. DATA TRAINING (10 record)
        // ──────────────────────────────────────────────────────────
        $trainingData = [
            [0.90, 0.88, 0.92, 0.85, 'A'],
            [0.85, 0.82, 0.87, 0.80, 'A'],
            [0.70, 0.65, 0.68, 0.72, 'B'],
            [0.72, 0.68, 0.70, 0.65, 'B'],
            [0.60, 0.58, 0.62, 0.55, 'B'],
            [0.45, 0.40, 0.42, 0.38, 'C'],
            [0.50, 0.48, 0.50, 0.45, 'C'],
            [0.35, 0.30, 0.38, 0.32, 'C'],
            [0.95, 0.92, 0.94, 0.90, 'A'],
            [0.55, 0.52, 0.58, 0.50, 'B'],
        ];
        foreach ($trainingData as [$tajwid, $kelancaran, $makhraj, $fashohah, $label]) {
            DataTraining::create([
                'fitur_tajwid'    => $tajwid,
                'fitur_kelancaran' => $kelancaran,
                'fitur_makhraj'   => $makhraj,
                'fitur_fashohah'  => $fashohah,
                'label_kelas'     => $label,
                'sumber_data'     => 'seeder_dummy',
                'is_valid'        => true,
                'tanggal_input'   => now()->subDays(rand(30, 90)),
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
                'kelas_target'      => 'C',
                'deskripsi'         => 'Mushaf Al-Qur\'an dengan penanda tajwid berwarna untuk memudahkan pemula.',
                'tips_belajar'      => 'Baca 1 halaman per hari dengan memperhatikan warna tajwid. Ulangi bacaan yang sulit dua kali.',
                'alasan_rekomendasi' => 'Cocok untuk siswa yang masih perlu perbaikan dasar tajwid dan makhraj.',
            ],
            [
                'nama_media'        => 'Buku Panduan Makhraj Huruf',
                'kelas_target'      => 'B',
                'deskripsi'         => 'Panduan lengkap makhraj 28 huruf hijaiyah disertai ilustrasi posisi lidah.',
                'tips_belajar'      => 'Fokus pada huruf ح خ ع غ yang sering salah. Latih di depan cermin setiap hari.',
                'alasan_rekomendasi' => 'Membantu siswa menyempurnakan makhraj huruf untuk naik ke kelas A.',
            ],
            [
                'nama_media'        => 'Juz Amma Terjemah',
                'kelas_target'      => 'A',
                'deskripsi'         => 'Juz Amma dilengkapi terjemah, asbabun nuzul, dan panduan hafalan per surah.',
                'tips_belajar'      => 'Pahami makna setiap ayat sebelum menghafal. Ini akan memperkuat hafalan jangka panjang.',
                'alasan_rekomendasi' => 'Untuk siswa berprestasi yang ingin memperdalam pemahaman makna hafalan.',
            ],
        ];

        $mediaDigital = [
            [
                'nama_media'        => 'Video Tajwid Pemula (YouTube)',
                'kelas_target'      => 'C',
                'url_link'          => 'https://www.youtube.com/watch?v=example1',
                'format_file'       => 'video/youtube',
                'deskripsi'         => 'Serial video pembelajaran tajwid dari dasar untuk pemula, total 20 episode.',
                'tips_belajar'      => 'Tonton sambil mengulang bacaan. Aktifkan subtitle untuk membantu pemahaman.',
                'alasan_rekomendasi' => 'Media visual interaktif untuk siswa yang butuh bimbingan tajwid intensif.',
            ],
            [
                'nama_media'        => 'Aplikasi Hafalan Qur\'an (Web)',
                'kelas_target'      => 'B',
                'url_link'          => 'https://quran.com',
                'format_file'       => 'web-app',
                'deskripsi'         => 'Platform web Quran.com dengan fitur audio, terjemah, dan mode hafalan.',
                'tips_belajar'      => 'Gunakan fitur repeat ayat dan aktifkan mode Tafsir untuk pemahaman lebih dalam.',
                'alasan_rekomendasi' => 'Mendukung latihan mandiri dengan umpan balik audio berkualitas tinggi.',
            ],
            [
                'nama_media'        => 'Audio Murottal Syaikh Sudais',
                'kelas_target'      => 'A',
                'url_link'          => 'https://download.quranicaudio.com/example',
                'format_file'       => 'audio/mp3',
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
        // Pemetaan sederhana ID Surat untuk fitur model 
        $surahIdMap = ['Al-Fatihah' => 1, 'Al-Baqarah' => 2, 'Al-Ikhlas' => 112, 'Al-Falaq' => 113, 'An-Nas' => 114, 'Al-Kafirun' => 109, 'Al-Kautsar' => 108, 'Al-Maun' => 107];
        
        foreach ($hafalanList as $idx => [$surah, $jmlAyat, $mediaId, $tgl]) {
            // Prediksi dummy seeder berdasarkan class A,B,C acak karena fitur berubah drastis
            $kelas = ['A', 'B', 'C'][rand(0, 2)];
            $confidence = round(rand(75, 97) / 100, 4);
            $notif    = $idx < 6;

            $vektor = [
                'siswa'       => $siswa1->id,
                'surah'       => $surahIdMap[$surah] ?? 1,
                'jumlah_ayat' => $jmlAyat,
                'id_media'    => $mediaId,
            ];

            $hasil = HasilKlasifikasi::create([
                'id_hafalan'          => $hafalanIds[$idx],
                'id_siswa'            => $siswa1->id,
                'id_model'            => $model->id,
                'kelas_prediksi'      => $kelas,
                'confidence_score'    => $confidence,
                'media_input'         => json_encode($vektor),
                'notifikasi_terkirim' => $notif,
                'tanggal_klasifikasi' => Carbon::parse($tgl)->addDays(2),
                'vector_svm'          => $vektor,
            ]);
            $hasilIds[$idx] = ['id' => $hasil->id, 'kelas' => $kelas];
        }
        $this->command->info('  → 8 hasil klasifikasi dibuat.');

        // ──────────────────────────────────────────────────────────
        // 7. REKOMENDASI SISWA
        // Kelas A → media [2] cetak + [5] digital
        // Kelas B → media [1] cetak + [4] digital
        // Kelas C → media [0] cetak + [3] digital
        // ──────────────────────────────────────────────────────────
        $mediaMapping = [
            'A' => [$mediaIds[2], $mediaIds[5]],
            'B' => [$mediaIds[1], $mediaIds[4]],
            'C' => [$mediaIds[0], $mediaIds[3]],
        ];

        $rekCounter = 0;
        foreach ($hasilIds as $idx => $data) {
            $pasangan = $mediaMapping[$data['kelas']];
            foreach ($pasangan as $prio => $mediaId) {
                $dilihat = $rekCounter < 10; // 10 pertama sudah dilihat
                RekomendasiSiswa::create([
                    'id_hasil'           => $data['id'],
                    'id_media'           => $mediaId,
                    'prioritas'          => $prio + 1,
                    'dilihat_siswa'      => $dilihat,
                    'tanggal_rekomendasi' => Carbon::parse($hafalanList[$idx][3])->addDays(2),
                ]);
                $rekCounter++;
            }
        }
        $this->command->info("  → {$rekCounter} rekomendasi siswa dibuat.");

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
