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
        Schema::table('tb_media_hafalan', function (Blueprint $table) {
            if (Schema::hasColumn('tb_media_hafalan', 'format_file')) {
                $table->dropColumn('format_file');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_media_hafalan', function (Blueprint $table) {
            //
        });
    }
};
