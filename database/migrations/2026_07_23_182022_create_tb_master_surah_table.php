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
        Schema::create('tb_master_surah', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('nomor_surah')->unsigned()->unique();
            $table->string('nama_surah', 100);
            $table->smallInteger('jumlah_ayat')->unsigned();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_master_surah');
    }
};
