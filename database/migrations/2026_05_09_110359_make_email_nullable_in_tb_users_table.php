<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah email menjadi nullable langsung via raw SQL (menghindari duplikat unique key)
        DB::statement('ALTER TABLE tb_users MODIFY email VARCHAR(150) NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE tb_users MODIFY email VARCHAR(150) NOT NULL');
    }
};
