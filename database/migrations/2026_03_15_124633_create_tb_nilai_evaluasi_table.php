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
        Schema::create('tb_nilai_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_hafalan');
            $table->float('nilai_makhraj')->nullable();
            $table->float('nilai_fashohah')->nullable();
            $table->float('nilai_total')->nullable();
            $table->text('catatan_guru')->nullable();
            $table->timestamp('tanggal_evaluasi')->useCurrent();

            $table->foreign('id_hafalan')
                ->references('id')
                ->on('tb_data_hafalan')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_nilai_evaluasi');
    }
};
