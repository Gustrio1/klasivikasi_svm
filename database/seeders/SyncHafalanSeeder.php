<?php

namespace Database\Seeders;

use App\Models\DataHafalan;
use App\Models\Guru;
use App\Models\MediaHafalan;
use App\Models\NilaiEvaluasi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SyncHafalanSeeder extends Seeder
{
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

    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🚀 SyncHafalanSeeder — Sinkronisasi & Pembaruan Hafalan');
        $this->command->info('════════════════════════════════════════════════════════════');

        $guruIds = Guru::pluck('id')->toArray();
        if (empty($guruIds)) {
            $this->command->error('❌ Tidak ada data Guru di database.');
            return;
        }

        $mediaCetakId    = MediaHafalan::where('nama_media', 'Media Cetak')->value('id');
        $mediaDigitalId  = MediaHafalan::where('nama_media', 'Media Digital')->value('id');
        $mediaAplikasiId = MediaHafalan::where('nama_media', 'Media Aplikasi')->value('id');

        if (!$mediaCetakId) {
            $media = MediaHafalan::create(['nama_media' => 'Media Cetak']);
            $mediaCetakId = $media->id;
        }
        if (!$mediaDigitalId) {
            $media = MediaHafalan::create(['nama_media' => 'Media Digital']);
            $mediaDigitalId = $media->id;
        }
        if (!$mediaAplikasiId) {
            $media = MediaHafalan::create(['nama_media' => 'Media Aplikasi']);
            $mediaAplikasiId = $media->id;
        }

        $csvPath = base_path('data_siswa.csv');
        if (!file_exists($csvPath)) {
            $this->command->error("❌ File tidak ditemukan: {$csvPath}");
            return;
        }

        $siswaData = $this->parseCSV($csvPath);
        $this->command->info('   ✔ Total siswa di CSV : ' . count($siswaData));
        
        $this->command->info('   🗑️ Menghapus data hafalan (beserta evaluasi & klasifikasi cascade)...');
        DB::table('tb_data_hafalan')->delete();
        $this->command->info('   ✔ Data hafalan berhasil dihapus sepenuhnya.');

        $ctrSiswa = 0;
        $ctrHafalan = 0;
        $ctrNilai = 0;

        $bar = $this->command->getOutput()->createProgressBar(count($siswaData));
        $bar->start();

        $maxExistingSiswa = User::where('role', 'siswa')
                                ->where('username', 'like', 'siswa_%')
                                ->count();
        $siswaCounter = max($maxExistingSiswa, count($siswaData));

        foreach ($siswaData as $idx => $data) {
            $nama = $data['nama'];
            $hafalanList = $data['hafalan'];
            
            $user = User::where('nama_lengkap', $nama)->where('role', 'siswa')->first();
            $guruIdSiswa = $guruIds[$idx % count($guruIds)];
            
            if (!$user) {
                $siswaCounter++;
                $username = 'siswa_' . str_pad($siswaCounter, 3, '0', STR_PAD_LEFT);
                $jenisKelamin = $this->tebakJenisKelamin($nama);
                
                $user = User::create([
                    'username'     => $username,
                    'password'     => Hash::make('password'),
                    'role'         => 'siswa',
                    'nama_lengkap' => $nama,
                    'is_active'    => true,
                ]);

                Siswa::create([
                    'id_user'       => $user->id,
                    'id_guru'       => $guruIdSiswa,
                    'nisn'          => '1000' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
                    'kelas'         => 'IX-' . chr(65 + ($idx % 4)),
                    'jenis_kelamin' => $jenisKelamin,
                    'tanggal_lahir' => (int) date('Y') - rand(15, 17),
                ]);
            }
            $ctrSiswa++;

            $siswa = $user->siswa;
            if (!$siswa) continue;
            
            // Tanggal mulai 350 hari yg lalu untuk disebar 1 per 7 hari
            $tanggalMulai = Carbon::now()->subDays(350);

            foreach ($hafalanList as $i => $h) {
                $mId = $mediaCetakId;
                if (stripos($h['media'], 'Digital') !== false) {
                    $mId = $mediaDigitalId;
                } elseif (stripos($h['media'], 'Aplikasi') !== false) {
                    $mId = $mediaAplikasiId;
                }

                $surah = $this->tebakSurah($h['ayat']);
                $tanggal = (clone $tanggalMulai)->addDays($i * 7);

                $hafRecord = DataHafalan::create([
                    'id_siswa'         => $siswa->id,
                    'id_guru'          => $siswa->id_guru ?? $guruIdSiswa,
                    'id_media'         => $mId,
                    'nama_surah'       => $surah,
                    'jumlah_ayat'      => $h['ayat'],
                    'periode_semester' => $this->getPeriodeSemester($tanggal),
                    'tanggal_input'    => $tanggal,
                    'created_at'       => $tanggal,
                    'updated_at'       => $tanggal,
                ]);
                $ctrHafalan++;

                // Bikin juga Nilai Evaluasi (karena evaluasi lama ikut terhapus cascade)
                $catatan = $this->getCatatan($h['ayat']);
                NilaiEvaluasi::create([
                    'id_hafalan'       => $hafRecord->id,
                    'catatan_guru'     => $catatan,
                    'tanggal_evaluasi' => (clone $tanggal)->addDay(),
                ]);
                $ctrNilai++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->command->info('');
        $this->command->info("🎉 Selesai! {$ctrSiswa} siswa diproses, {$ctrHafalan} hafalan dan {$ctrNilai} nilai dievaluasi.");
    }

    private function parseCSV(string $path): array
    {
        $siswaData = [];
        $currentNama = null;
        $rowNum = 0;

        $handle = fopen($path, 'r');
        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            if ($rowNum <= 2) continue; 

            $no      = isset($row[0]) ? trim($row[0]) : '';
            $nama    = isset($row[2]) ? trim($row[2]) : '';
            $ayat    = isset($row[3]) ? (int) trim($row[3]) : 0;
            $media   = isset($row[4]) ? trim($row[4]) : '';

            if ($no !== '' && $nama !== '') {
                $currentNama = $nama;
                $siswaData[] = [
                    'nama'    => $currentNama,
                    'hafalan' => []
                ];
            }

            if ($currentNama !== null && $ayat > 0) {
                $lastIdx = count($siswaData) - 1;
                $urut = isset($row[1]) && trim($row[1]) !== '' 
                            ? (int) trim($row[1]) 
                            : (count($siswaData[$lastIdx]['hafalan']) + 1);
                            
                $siswaData[$lastIdx]['hafalan'][] = [
                    'urut'  => $urut,
                    'ayat'  => $ayat,
                    'media' => $media,
                ];
            }
        }
        fclose($handle);
        return $siswaData;
    }

    private function tebakJenisKelamin(string $nama): string
    {
        $nama = strtoupper($nama);
        $kataPria = ['MUHAMMAD', 'MUHAMAD', 'MOH', 'MHD', 'ADITYA', 'AGUNG', 'BAGAS', 'DIGA', 'FIKRI', 'ILHAM', 'ANDIKA'];
        foreach ($kataPria as $kata) {
            if (str_starts_with($nama, $kata)) return 'L';
        }
        return 'P';
    }

    private function tebakSurah(int $ayat): string
    {
        $terbaik = 'Al-Ikhlas';
        $selisihTerkecil = 999;
        foreach ($this->surahPool as $s) {
            $diff = abs($s['ayat'] - $ayat);
            if ($diff < $selisihTerkecil) {
                $selisihTerkecil = $diff;
                $terbaik = $s['nama'];
            }
        }
        return $terbaik;
    }

    private function getCatatan(int $ayat): string
    {
        if ($ayat >= 18) {
            return $this->catatanTinggi[array_rand($this->catatanTinggi)];
        } elseif ($ayat >= 12) {
            return $this->catatanSedang[array_rand($this->catatanSedang)];
        }
        return $this->catatanRendah[array_rand($this->catatanRendah)];
    }

    private function getPeriodeSemester(Carbon $tanggal): string
    {
        $bulan = $tanggal->month;
        $tahun = $tanggal->year;
        return $bulan >= 7
            ? 'Ganjil ' . $tahun . '/' . ($tahun + 1)
            : 'Genap ' . ($tahun - 1) . '/' . $tahun;
    }
}
