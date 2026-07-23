<?php

namespace Database\Seeders;

use App\Models\DataHafalan;
use App\Models\MediaHafalan;
use App\Models\NilaiEvaluasi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class DataSiswaBaruSeeder extends Seeder
{
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
        $this->command->info('🚀 DataSiswaBaruSeeder — Import Data dari JSON');
        $this->command->info('════════════════════════════════════════════════════════════');

        $guruIds = $this->pastikanGuru();
        $guruId  = $guruIds[0];

        [$mediaCetakId, $mediaDigitalId] = $this->pastikanMedia();

        $jsonPath = base_path('Data_siswa_baru_cleaned.json');

        if (!file_exists($jsonPath)) {
            $this->command->error("❌ File tidak ditemukan: {$jsonPath}");
            return;
        }

        $siswaData = json_decode(File::get($jsonPath), true);

        $this->command->info('   ✔ Guru tersedia   : ' . count($guruIds));
        $this->command->info('   ✔ Media Cetak ID  : ' . $mediaCetakId);
        $this->command->info('   ✔ Media Digital ID: ' . $mediaDigitalId);
        $this->command->info('   ✔ Total siswa JSON: ' . count($siswaData));
        $this->command->info('');

        $ctrSiswa    = 0;
        $ctrHafalan  = 0;
        $ctrNilai    = 0;
        $ctrSkip     = 0;

        $bar = $this->command->getOutput()->createProgressBar(count($siswaData));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% — %message%');
        $bar->setMessage('Memulai...');
        $bar->start();

        foreach ($siswaData as $idx => $data) {
            $nama     = $data['nama'];
            $hafalan  = $data['hafalan']; 
            $urut     = $idx + 1;
            $username = 'siswabaru_' . str_pad($urut, 3, '0', STR_PAD_LEFT);

            $bar->setMessage("#{$urut} {$nama}");

            if (User::where('username', $username)->exists()) {
                $ctrSkip++;
                $bar->advance();
                continue;
            }

            $jenisKelamin = $data['jenis_kelamin'];
            $tahunLahir   = (int) date('Y') - $data['umur'];
            $kelas        = $data['kelas'];

            $guruIdSiswa = $guruIds[$idx % count($guruIds)];

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
                'nisn'          => str_pad(2000000000 + $idx, 10, '0', STR_PAD_LEFT),
                'kelas'         => $kelas,
                'jenis_kelamin' => $jenisKelamin,
                'tanggal_lahir' => $tahunLahir,
            ]);
            $ctrSiswa++;

            $tanggalMulai = Carbon::now()->subDays(350);

            foreach ($hafalan as $i => $h) {
                $ayat      = $h['ayat'];
                $mediaJson = $h['media'];  
                $suratJson = $h['surat'];
                $tanggal   = (clone $tanggalMulai)->addDays($i * 7);

                if (str_contains(strtolower($mediaJson), 'digital')) {
                    $mediaId = $mediaDigitalId;
                } else {
                    $mediaId = $mediaCetakId;
                }

                $hafRecord = DataHafalan::create([
                    'id_siswa'         => $siswa->id,
                    'id_guru'          => $guruIdSiswa,
                    'id_media'         => $mediaId,
                    'nama_surah'       => $suratJson,
                    'jumlah_ayat'      => $ayat,
                    'periode_semester' => $this->getPeriodeSemester($tanggal),
                    'tanggal_input'    => $tanggal,
                ]);
                $ctrHafalan++;

                $catatan = $this->getCatatan($ayat);
                NilaiEvaluasi::create([
                    'id_hafalan'       => $hafRecord->id,
                    'catatan_guru'     => $catatan,
                    'tanggal_evaluasi' => (clone $tanggal)->addDay(),
                ]);
                $ctrNilai++;
            }

            $bar->advance();
        }

        $bar->setMessage('Selesai! ✅');
        $bar->finish();

        $this->command->info('');
        $this->command->info('');
        $this->command->info('✅ DataSiswaBaruSeeder selesai!');
        $this->command->info('════════════════════════════════════════════════════════════');
        $this->command->table(
            ['Keterangan', 'Jumlah'],
            [
                ['Siswa dari JSON berhasil dibuat',   $ctrSiswa],
                ['Siswa dilewati (sudah ada)',        $ctrSkip],
                ['──────────────────────────────',  '──────'],
                ['Total Hafalan Dibuat',              number_format($ctrHafalan)],
                ['Total Nilai Evaluasi Dibuat',       number_format($ctrNilai)],
            ]
        );
        $this->command->info('');
    }

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
                    'nip'            => $g['nip'] ?? null,
                    'mata_pelajaran' => 'Tahfidz Al-Qur\'an',
                    'no_telp'        => '08123456' . str_pad(count($ids) + 1, 4, '0', STR_PAD_LEFT),
                    'is_active'      => true,
                ]
            );
            $ids[] = $guru->id;
        }
        return $ids;
    }

    private function pastikanMedia(): array
    {
        $mediaCetak = \App\Models\MediaHafalan::where('jenis_media', 'cetak')->first();
        $mediaDigital = \App\Models\MediaHafalan::where('jenis_media', 'digital')->first();

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
        return 'Ganjil 2025/2026';
    }
}
