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
        Schema::table('tb_nilai_evaluasi', function (Blueprint $table) {
            $table->dropColumn(['nilai_makhraj', 'nilai_fashohah', 'nilai_total']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_nilai_evaluasi', function (Blueprint $table) {
            $table->float('nilai_makhraj')->nullable();
            $table->float('nilai_fashohah')->nullable();
            $table->float('nilai_total')->nullable();
        });
    }
};
