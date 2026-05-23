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
        Schema::create('tb_rekomendasi_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_hasil');
            $table->unsignedBigInteger('id_media');
            $table->unsignedTinyInteger('prioritas')->default(1);
            $table->boolean('dilihat_siswa')->default(false);
            $table->timestamp('tanggal_rekomendasi')->useCurrent();

            $table->foreign('id_hasil')
                ->references('id')
                ->on('tb_hasil_klasifikasi')
                ->onDelete('cascade');

            $table->foreign('id_media')
                ->references('id')
                ->on('tb_media_hafalan')
                ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_rekomendasi_siswa');
    }
};
