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
        Schema::create('tb_log_evaluasi_model', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_model');
            $table->float('akurasi')->nullable();
            $table->float('precision')->nullable();
            $table->float('recall')->nullable();
            $table->float('f1_score')->nullable();
            $table->json('confusion_matrix')->nullable();
            $table->timestamp('tanggal_evaluasi')->useCurrent();

            $table->foreign('id_model')
                ->references('id')
                ->on('tb_model_svm')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_log_evaluasi_model');
    }
};
