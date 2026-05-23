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
        Schema::create('tb_laporan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_guru');
            $table->string('judul_laporan', 200);
            $table->string('periode', 50)->nullable();
            $table->string('file_path', 500)->nullable();
            $table->timestamp('tanggal_cetak')->useCurrent();

            $table->foreign('id_siswa')
                ->references('id')
                ->on('tb_siswa')
                ->onDelete('cascade');

            $table->foreign('id_guru')
                ->references('id')
                ->on('tb_guru')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_laporan');
    }
};
