<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Truncate tables to avoid foreign key constraints errors during restructure
        // Since the old classification was per-hafalan, it's incompatible with the new per-semester logic.
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tb_hasil_klasifikasi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Add periode_semester to tb_data_hafalan
        Schema::table('tb_data_hafalan', function (Blueprint $table) {
            $table->string('periode_semester', 50)->nullable()->after('jumlah_ayat')->comment('Misal: Genap 2026/2027');
        });

        // 3. Alter tb_hasil_klasifikasi
        Schema::table('tb_hasil_klasifikasi', function (Blueprint $table) {
            // Drop foreign key id_hafalan safely
            $table->dropForeign(['id_hafalan']);
            $table->dropColumn('id_hafalan');
            
            // Add semester tracking and total_surah
            $table->string('periode_semester', 50)->after('id_siswa');
            $table->integer('total_surah')->after('periode_semester')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tb_hasil_klasifikasi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::table('tb_hasil_klasifikasi', function (Blueprint $table) {
            $table->dropColumn('total_surah');
            $table->dropColumn('periode_semester');
            $table->unsignedBigInteger('id_hafalan')->after('id');
            $table->foreign('id_hafalan')->references('id')->on('tb_data_hafalan')->onDelete('cascade');
        });

        Schema::table('tb_data_hafalan', function (Blueprint $table) {
            $table->dropColumn('periode_semester');
        });
    }
};
