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
        // 1. Drop kelas_target from tb_media_hafalan
        if (Schema::hasColumn('tb_media_hafalan', 'kelas_target')) {
            Schema::table('tb_media_hafalan', function (Blueprint $table) {
                $table->dropColumn('kelas_target');
            });
        }

        // 2. Modify label_kelas in tb_data_training
        if (Schema::hasColumn('tb_data_training', 'label_kelas')) {
            Schema::table('tb_data_training', function (Blueprint $table) {
                $table->dropColumn('label_kelas');
            });
        }
        if (!Schema::hasColumn('tb_data_training', 'label_kelas')) {
            Schema::table('tb_data_training', function (Blueprint $table) {
                $table->enum('label_kelas', ['Lulus', 'Tidak Lulus'])->after('id_media');
            });
        }

        // 3. Modify kelas_prediksi in tb_hasil_klasifikasi
        if (Schema::hasColumn('tb_hasil_klasifikasi', 'kelas_prediksi')) {
            Schema::table('tb_hasil_klasifikasi', function (Blueprint $table) {
                $table->dropColumn('kelas_prediksi');
            });
        }
        if (!Schema::hasColumn('tb_hasil_klasifikasi', 'kelas_prediksi')) {
            Schema::table('tb_hasil_klasifikasi', function (Blueprint $table) {
                $table->enum('kelas_prediksi', ['Lulus', 'Tidak Lulus'])->after('id_model');
            });
        }

        // 4. Drop tb_rekomendasi_siswa
        Schema::dropIfExists('tb_rekomendasi_siswa');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-create tb_rekomendasi_siswa
        Schema::create('tb_rekomendasi_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_siswa')->constrained('tb_siswa')->cascadeOnDelete();
            $table->foreignId('id_media')->constrained('tb_media_hafalan')->cascadeOnDelete();
            $table->timestamp('tanggal_rekomendasi')->useCurrent();
            $table->timestamps();
        });

        // Revert kelas_prediksi in tb_hasil_klasifikasi
        Schema::table('tb_hasil_klasifikasi', function (Blueprint $table) {
            $table->dropColumn('kelas_prediksi');
        });
        Schema::table('tb_hasil_klasifikasi', function (Blueprint $table) {
            $table->enum('kelas_prediksi', ['A', 'B', 'C'])->after('id_model');
        });

        // Revert label_kelas in tb_data_training
        Schema::table('tb_data_training', function (Blueprint $table) {
            $table->dropColumn('label_kelas');
        });
        Schema::table('tb_data_training', function (Blueprint $table) {
            $table->enum('label_kelas', ['A', 'B', 'C'])->after('id_media');
        });

        // Add back kelas_target to tb_media_hafalan
        Schema::table('tb_media_hafalan', function (Blueprint $table) {
            $table->enum('kelas_target', ['A', 'B', 'C'])->after('jenis_media');
        });
    }
};
