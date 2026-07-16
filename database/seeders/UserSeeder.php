<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ───────────────────────────────────────────────
        $admin = User::create([
            'username'     => 'admin',
            'password'     => Hash::make('password'),
            'role'         => 'admin',
            'nama_lengkap' => 'Administrator Sistem',
            'email'        => 'admin@hafalan.test',
            'is_active'    => true,
        ]);

        // ─── Guru ─────────────────────────────────────────────────
        $guruData = [
            [
                'user' => [
                    'username'     => 'guru1',
                    'nama_lengkap' => 'Ustadz Ahmad Fauzi',
                    'email'        => 'guru1@hafalan.test',
                ],
                'guru' => [
                    'nip'            => '19850101001',
                    'mata_pelajaran' => 'Tahfidz Al-Qur\'an',
                    'no_telp'        => '081234567001',
                ],
            ],
            [
                'user' => [
                    'username'     => 'guru2',
                    'nama_lengkap' => 'Ustadzah Siti Aminah',
                    'email'        => 'guru2@hafalan.test',
                ],
                'guru' => [
                    'nip'            => '19900215002',
                    'mata_pelajaran' => 'Tajwid',
                    'no_telp'        => '081234567002',
                ],
            ],
            [
                'user' => [
                    'username'     => 'guru3',
                    'nama_lengkap' => 'Ustadz Hasan Basri',
                    'email'        => 'guru3@hafalan.test',
                ],
                'guru' => [
                    'nip'            => '19880320003',
                    'mata_pelajaran' => 'Tahfidz & Tilawah',
                    'no_telp'        => '081234567003',
                ],
            ],
        ];

        $guruIds = [];
        foreach ($guruData as $data) {
            $user = User::create(array_merge($data['user'], [
                'password'  => Hash::make('password'),
                'role'      => 'guru',
                'is_active' => true,
            ]));
            $guru = Guru::create(array_merge($data['guru'], [
                'id_user'   => $user->id,
                'is_active' => true,
            ]));
            $guruIds[] = $guru->id;
        }

        // ─── Siswa ────────────────────────────────────────────────
        $siswaData = [
            [
                'user' => [
                    'username'     => 'siswa1',
                    'nama_lengkap' => 'Muhammad Rizki Ramadhan',
                    'email'        => 'siswa1@hafalan.test',
                ],
                'siswa' => [
                    'nisn'          => '0012345678',
                    'kelas'         => 'VII-A',
                    'jenis_kelamin' => 'L',
                    'tanggal_lahir' => 2010,
                    'id_guru'       => $guruIds[0],
                ],
            ],
            [
                'user' => [
                    'username'     => 'siswa2',
                    'nama_lengkap' => 'Fatimah Az-Zahra',
                    'email'        => 'siswa2@hafalan.test',
                ],
                'siswa' => [
                    'nisn'          => '0012345679',
                    'kelas'         => 'VII-A',
                    'jenis_kelamin' => 'P',
                    'tanggal_lahir' => 2010,
                    'id_guru'       => $guruIds[0],
                ],
            ],
            [
                'user' => [
                    'username'     => 'siswa3',
                    'nama_lengkap' => 'Abdullah Hakim',
                    'email'        => 'siswa3@hafalan.test',
                ],
                'siswa' => [
                    'nisn'          => '0012345680',
                    'kelas'         => 'VIII-B',
                    'jenis_kelamin' => 'L',
                    'tanggal_lahir' => 2009,
                    'id_guru'       => $guruIds[1],
                ],
            ],
            [
                'user' => [
                    'username'     => 'siswa4',
                    'nama_lengkap' => 'Aisyah Nur Fadilah',
                    'email'        => 'siswa4@hafalan.test',
                ],
                'siswa' => [
                    'nisn'          => '0012345681',
                    'kelas'         => 'VIII-B',
                    'jenis_kelamin' => 'P',
                    'tanggal_lahir' => 2009,
                    'id_guru'       => $guruIds[1],
                ],
            ],
            [
                'user' => [
                    'username'     => 'siswa5',
                    'nama_lengkap' => 'Yusuf Al-Farisi',
                    'email'        => 'siswa5@hafalan.test',
                ],
                'siswa' => [
                    'nisn'          => '0012345682',
                    'kelas'         => 'IX-C',
                    'jenis_kelamin' => 'L',
                    'tanggal_lahir' => 2008,
                    'id_guru'       => $guruIds[2],
                ],
            ],
        ];

        foreach ($siswaData as $data) {
            $user = User::create(array_merge($data['user'], [
                'password'  => Hash::make('password'),
                'role'      => 'siswa',
                'is_active' => true,
            ]));
            Siswa::create(array_merge($data['siswa'], [
                'id_user' => $user->id,
            ]));
        }

        $this->command->info('✅ UserSeeder: 1 admin, 3 guru, 5 siswa berhasil dibuat.');
    }
}
