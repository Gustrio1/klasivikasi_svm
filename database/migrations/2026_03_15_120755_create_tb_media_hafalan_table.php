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
        Schema::create('tb_media_hafalan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_media', 200);

            // REVISI: VARCHAR -> ENUM untuk memastikan hanya 2 nilai yang valid
            $table->enum('jenis_media', ['cetak', 'digital']);

            $table->enum('kelas_target', ['A', 'B', 'C']);

            // KOLOM BARU: hanya diisi jika jenis_media = 'digital'
            $table->string('url_link', 500)->nullable()->comment('Link Aplikasi jika jenis_media = digital');
            $table->text('deskripsi')->nullable();
            $table->text('tips_belajar')->nullable();
            $table->text('alasan_rekomendasi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('tanggal_input')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_media_hafalan');
    }
};
