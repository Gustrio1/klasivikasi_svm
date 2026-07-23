<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Urutan seeder: Users & relasi → Data hafalan & SVM
     */
    public function run(): void
    {
        // Nonaktifkan FK check agar truncate berjalan tanpa error
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate semua tabel terkait (urutan dari anak ke induk)
        // Menggunakan raw TRUNCATE IF EXISTS agar aman saat fresh migration
        $tables = [
            'tb_rekomendasi_siswa',
            'tb_hasil_klasifikasi',
            'tb_nilai_evaluasi',
            'tb_data_hafalan',
            'tb_log_evaluasi_model',
            'tb_media_hafalan',
            'tb_model_svm',
            'tb_data_training',
            'tb_laporan',
            'tb_siswa',
            'tb_guru',
            'tb_users',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::statement("TRUNCATE TABLE `{$table}`");
            }
        }

        // Aktifkan kembali FK check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            MasterSurahSeeder::class,           // 37 surat Juz 30
            UserSeeder::class,                  // users, guru, siswa
            HafalanSeeder::class,               // data_training, model_svm, media_hafalan,
                                                // data_hafalan, nilai_evaluasi,
                                                // hasil_klasifikasi, log_evaluasi_model
            DataSiswaBaruSeeder::class,         // 35 siswa baru dari JSON
        ]);

        $this->command->newLine();
        $this->command->info('🎉 Seeding selesai! Login dengan:');
        $this->command->table(
            ['Role', 'Username', 'Password', 'URL'],
            [
                ['Admin',  'admin',  'password', '/dashboard'],
                ['Guru',   'guru1',  'password', '/dashboard'],
                ['Siswa',  'siswa1', 'password', '/dashboard'],
            ]
        );
    }
}
