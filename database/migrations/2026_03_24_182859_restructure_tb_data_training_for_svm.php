<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Restrukturisasi tabel tb_data_training:
 * 
 * HAPUS kolom fitur lama yang tidak sesuai model SVM:
 *   - fitur_tajwid, fitur_kelancaran, fitur_makhraj, fitur_fashohah
 * 
 * TAMBAH kolom fitur baru sesuai alur klasifikasi SVM:
 *   - fitur_jumlah_ayat  → jumlah ayat yang dihafal (INT)
 *   - fitur_usia         → usia siswa dalam tahun (TINYINT)
 *   - id_media           → FK ke tb_media_hafalan
 */
return new class extends Migration 
{
    public function up(): void
    {
        Schema::table('tb_data_training', function (Blueprint $table) {

            // ── Hapus kolom lama jika masih ada ──
            $oldCols = ['fitur_tajwid', 'fitur_kelancaran', 'fitur_makhraj', 'fitur_fashohah'];
            $toDrop = array_filter($oldCols, fn($c) => Schema::hasColumn('tb_data_training', $c));
            if (!empty($toDrop)) {
                $table->dropColumn(array_values($toDrop));
            }

            // ── Tambah kolom fitur baru ──
            if (!Schema::hasColumn('tb_data_training', 'fitur_jumlah_ayat')) {
                $table->unsignedInteger('fitur_jumlah_ayat')
                    ->default(0)
                    ->after('id')
                    ->comment('Jumlah ayat yang dihafal dalam satu sesi');
            }

            if (!Schema::hasColumn('tb_data_training', 'fitur_usia')) {
                $table->unsignedTinyInteger('fitur_usia')
                    ->default(0)
                    ->after('fitur_jumlah_ayat')
                    ->comment('Usia siswa dalam tahun saat data direkam');
            }

            if (!Schema::hasColumn('tb_data_training', 'id_media')) {
                $table->unsignedBigInteger('id_media')
                    ->nullable()
                    ->after('fitur_usia')
                    ->comment('FK ke tb_media_hafalan');

                $table->foreign('id_media')
                    ->references('id')
                    ->on('tb_media_hafalan')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_data_training', function (Blueprint $table) {
            // Hapus FK & kolom baru
            if (Schema::hasColumn('tb_data_training', 'id_media')) {
                $table->dropForeign(['id_media']);
                $table->dropColumn(['fitur_jumlah_ayat', 'fitur_usia', 'id_media']);
            }

            // Kembalikan kolom lama
            if (!Schema::hasColumn('tb_data_training', 'fitur_tajwid')) {
                $table->float('fitur_tajwid')->after('id');
                $table->float('fitur_kelancaran')->after('fitur_tajwid');
                $table->float('fitur_makhraj')->after('fitur_kelancaran');
                $table->float('fitur_fashohah')->after('fitur_makhraj');
            }
        });
    }
};
