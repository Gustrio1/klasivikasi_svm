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
        Schema::create('tb_model_svm', function (Blueprint $table) {
            $table->id();
            $table->string('versi_model', 50)->unique();
            $table->enum('kernel_type', ['rbf', 'linear'])->default('rbf');
            $table->float('parameter_C')->default(1.0);
            $table->float('parameter_gamma')->default(0.1);
            $table->float('akurasi_model')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('tanggal_training')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_model_svm');
    }
};
