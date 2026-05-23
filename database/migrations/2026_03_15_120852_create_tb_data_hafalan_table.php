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
        Schema::create('tb_data_hafalan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_guru');
            $table->unsignedBigInteger('id_media')->nullable()->comment('Media yang digunakan siswa saat menghafal');
            $table->string('nama_surah', 100);
            $table->unsignedSmallInteger('jumlah_ayat')->comment('Total ayat yang dihafal');
            $table->timestamp('tanggal_input')->useCurrent();

            $table->foreign('id_siswa')
                ->references('id')
                ->on('tb_siswa')
                ->onDelete('cascade');

            $table->foreign('id_guru')
                ->references('id')
                ->on('tb_guru')
                ->onDelete('restrict');

            $table->foreign('id_media')
                ->references('id')
                ->on('tb_media_hafalan')
                ->onDelete('set null');
                
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_hafalan');
    }
};
