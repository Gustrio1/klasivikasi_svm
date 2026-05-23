<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah tanggal_lahir dari DATE menjadi SMALLINT UNSIGNED (simpan tahun saja)
        // Konversi data yang sudah ada: ambil YEAR dari DATE
        DB::statement('ALTER TABLE tb_siswa MODIFY tanggal_lahir SMALLINT UNSIGNED NULL');
        DB::statement('UPDATE tb_siswa SET tanggal_lahir = YEAR(tanggal_lahir) WHERE tanggal_lahir IS NOT NULL AND tanggal_lahir != 0');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE tb_siswa MODIFY tanggal_lahir DATE NULL');
    }
};
