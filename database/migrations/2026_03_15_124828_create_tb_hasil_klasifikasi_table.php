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
        Schema::create('tb_hasil_klasifikasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_hafalan');
            $table->unsignedBigInteger('id_siswa');
            $table->unsignedBigInteger('id_model');
            $table->enum('kelas_prediksi', ['A', 'B', 'C']);
            $table->float('confidence_score')->nullable();
            $table->text('media_input')->nullable();
            $table->boolean('notifikasi_terkirim')->default(false);
            $table->timestamp('tanggal_klasifikasi')->useCurrent();
            $table->json('vector_svm')->nullable();

            $table->foreign('id_hafalan')
                ->references('id')
                ->on('tb_data_hafalan')
                ->onDelete('cascade');

            $table->foreign('id_siswa')
                ->references('id')
                ->on('tb_siswa')
                ->onDelete('cascade');

            $table->foreign('id_model')
                ->references('id')
                ->on('tb_model_svm')
                ->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_hasil_klasifikasi');
    }
};
