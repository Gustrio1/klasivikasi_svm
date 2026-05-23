<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_data_training', function (Blueprint $table) {
            $table->renameColumn('fitur_jumlah_ayat', 'fitur_total_surah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_data_training', function (Blueprint $table) {
            $table->renameColumn('fitur_total_surah', 'fitur_jumlah_ayat');
        });
    }
};
