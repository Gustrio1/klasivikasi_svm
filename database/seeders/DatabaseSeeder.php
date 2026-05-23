<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::table('tb_rekomendasi_siswa')->truncate();
        DB::table('tb_hasil_klasifikasi')->truncate();
        DB::table('tb_nilai_evaluasi')->truncate();
        DB::table('tb_data_hafalan')->truncate();
        DB::table('tb_log_evaluasi_model')->truncate();
        DB::table('tb_media_hafalan')->truncate();
        DB::table('tb_model_svm')->truncate();
        DB::table('tb_data_training')->truncate();
        DB::table('tb_laporan')->truncate();
        DB::table('tb_siswa')->truncate();
        DB::table('tb_guru')->truncate();
        DB::table('tb_users')->truncate();

        // Aktifkan kembali FK check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Jalankan seeder dalam urutan yang benar
        $this->call([
            UserSeeder::class,    // users, guru, siswa
            HafalanSeeder::class, // data_training, model_svm, media_hafalan,
                                  // data_hafalan, nilai_evaluasi,
                                  // hasil_klasifikasi, rekomendasi_siswa,
                                  // log_evaluasi_model
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
