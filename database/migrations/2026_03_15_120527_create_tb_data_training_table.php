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
        Schema::create('tb_data_training', function (Blueprint $table) {
            $table->id();
            $table->float('fitur_tajwid');
            $table->float('fitur_kelancaran');
            $table->float('fitur_makhraj');
            $table->float('fitur_fashohah');
            $table->enum('label_kelas', ['A', 'B', 'C']);
            $table->string('sumber_data', 150)->nullable();
            $table->boolean('is_valid')->default(true);
            $table->timestamp('tanggal_input')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_data_training');
    }
};
