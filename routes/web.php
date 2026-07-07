<?php

use Illuminate\Support\Facades\Route;

// ─── Controllers ─────────────────────────────────────────────────────────────
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\DataHafalanController;
use App\Http\Controllers\NilaiEvaluasiController;
use App\Http\Controllers\HasilKlasifikasiController;
use App\Http\Controllers\MediaHafalanController;

use App\Http\Controllers\DataTrainingController;
use App\Http\Controllers\ModelSvmController;
use App\Http\Controllers\LogEvaluasiModelController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SvmPerhitunganController;

// =============================================================================
// REDIRECT ROOT
// =============================================================================
Route::redirect('/', '/dashboard');

// =============================================================================
// PUBLIK — Tanpa middleware auth
// =============================================================================
Route::get('/login', [LoginController::class , 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class , 'login'])->name('login.post');
Route::post('/logout', [LoginController::class , 'logout'])->name('logout');

// =============================================================================
// AUTH — Semua role yang sudah login
// =============================================================================
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');


});

// =============================================================================
// ADMIN — Prefix: /admin | Middleware: auth, role:admin
// =============================================================================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ── User Management ───────────────────────────────────────────────────────
        Route::resource('users', UserController::class);

        // ── Guru Management ───────────────────────────────────────────────────────
        Route::resource('guru', GuruController::class);

        // ── Siswa Management ──────────────────────────────────────────────────────
        Route::resource('siswa', SiswaController::class);

        // ── Data Training ─────────────────────────────────────────────────────────
        Route::post('data-training/import', [DataTrainingController::class , 'import'])
            ->name('data-training.import');
        Route::resource('data-training', DataTrainingController::class);

        // ── Model SVM ─────────────────────────────────────────────────────────────
        Route::post('model-svm/{modelSvm}/aktivasi', [ModelSvmController::class , 'aktivasi'])
            ->name('model-svm.aktivasi');
        Route::resource('model-svm', ModelSvmController::class)
            ->except(['edit', 'update']);

        Route::resource('log-evaluasi', LogEvaluasiModelController::class)
            ->only(['index', 'show', 'store']);

        // ── Media Hafalan ─────────────────────────────────────────────────────────
        Route::resource('media-hafalan', MediaHafalanController::class);

        // ── Data Hafalan ──────────────────────────────────────────────────────────
        Route::resource('hafalan', DataHafalanController::class)
            ->only(['index', 'show'])
            ->parameters(['hafalan' => 'dataHafalan']);

        // ── Laporan ───────────────────────────────────────────────────────────────
        Route::get('laporan/{laporan}/download', [LaporanController::class , 'download'])
            ->name('laporan.download');
        Route::resource('laporan', LaporanController::class)
            ->except(['create', 'edit']);

        // ── Hasil Klasifikasi ─────────────────────────────────────────────────────
        Route::patch('hasil-klasifikasi/{hasilKlasifikasi}/notifikasi', [HasilKlasifikasiController::class , 'updateNotifikasi'])
            ->name('hasil-klasifikasi.notifikasi');
        Route::resource('hasil-klasifikasi', HasilKlasifikasiController::class)
            ->only(['index', 'show']);

        // ── Perhitungan SVM ───────────────────────────────────────────────────
        Route::get('perhitungan-svm', [SvmPerhitunganController::class, 'index'])
            ->name('perhitungan-svm.index');
    });

// =============================================================================
// GURU — Prefix: /guru | Middleware: auth, role:guru
// =============================================================================
Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {

        // ── Manajemen Siswa ───────────────────────────────────────────────────────
        Route::prefix('siswa')->name('siswa.')->group(function () {
            Route::get('/', [SiswaController::class , 'index'])->name('index');
            Route::get('/create', [SiswaController::class , 'create'])->name('create');
            Route::post('/', [SiswaController::class , 'store'])->name('store');
            Route::get('/{siswa}', [SiswaController::class , 'show'])->name('show');
            Route::get('/{siswa}/edit', [SiswaController::class , 'edit'])->name('edit');
            Route::put('/{siswa}', [SiswaController::class , 'update'])->name('update');
        }
        );

        // ── Data Hafalan ──────────────────────────────────────────────────────────
        Route::resource('hafalan', DataHafalanController::class)
            ->parameters(['hafalan' => 'dataHafalan']);

        // ── Nilai Evaluasi ────────────────────────────────────────────────────────
        Route::prefix('nilai-evaluasi')->name('nilai-evaluasi.')->group(function () {
            Route::get('/{nilaiEvaluasi}', [NilaiEvaluasiController::class , 'show'])->name('show');
            Route::post('/', [NilaiEvaluasiController::class , 'store'])->name('store');
            Route::put('/{nilaiEvaluasi}', [NilaiEvaluasiController::class , 'update'])->name('update');
        }
        );

        // ── Hasil Klasifikasi ─────────────────────────────────────────────────────
        Route::prefix('hasil-klasifikasi')->name('hasil-klasifikasi.')->group(function () {
            Route::get('/', [HasilKlasifikasiController::class , 'index'])->name('index');
            Route::post('/evaluasi-semester', [HasilKlasifikasiController::class , 'klasifikasiSemester'])->name('evaluasi-semester');
            Route::get('/{hasilKlasifikasi}', [HasilKlasifikasiController::class , 'show'])->name('show');
        }
        );

        // ── Perhitungan SVM ───────────────────────────────────────────────────
        Route::get('perhitungan-svm', [SvmPerhitunganController::class, 'index'])
            ->name('perhitungan-svm.index');

        // ── Media Hafalan ─────────────────────────────────────────────────────────
        Route::resource('media-hafalan', MediaHafalanController::class);

        // ── Laporan ───────────────────────────────────────────────────────────────
        Route::get('laporan/{laporan}/download', [LaporanController::class , 'download'])
            ->name('laporan.download');
        Route::resource('laporan', LaporanController::class)
            ->except(['edit', 'update']);
    });

// =============================================================================
// SISWA — Prefix: /siswa | Middleware: auth, role:siswa
// =============================================================================
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        // ── Hafalan (read-only) ───────────────────────────────────────────────────
        Route::prefix('hafalan')->name('hafalan.')->group(function () {
            Route::get('/', [DataHafalanController::class , 'index'])->name('index');
            Route::get('/{dataHafalan}', [DataHafalanController::class , 'show'])->name('show');
        }
        );

        // ── Hasil Klasifikasi ─────────────────────────────────────────────────────
        Route::prefix('hasil-klasifikasi')->name('hasil-klasifikasi.')->group(function () {
            Route::get('/', [HasilKlasifikasiController::class , 'index'])->name('index');
            Route::get('/{hasilKlasifikasi}', [HasilKlasifikasiController::class , 'show'])->name('show');
        }
        );


        // ── Nilai Evaluasi (read-only) ────────────────────────────────────────────
        Route::get('nilai-evaluasi/{nilaiEvaluasi}', [NilaiEvaluasiController::class , 'show'])
            ->name('nilai-evaluasi.show');

        // ── Laporan (read-only) ───────────────────────────────────────────────────
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class , 'index'])->name('index');
            Route::get('/{laporan}', [LaporanController::class , 'show'])->name('show');
            Route::get('/{laporan}/download', [LaporanController::class , 'download'])->name('download');
        }
        );

        // ── Media Hafalan (read-only) ─────────────────────────────────────────────
        Route::prefix('media-hafalan')->name('media-hafalan.')->group(function () {
            Route::get('/', [MediaHafalanController::class , 'index'])->name('index');
            Route::get('/{mediaHafalan}', [MediaHafalanController::class , 'show'])->name('show');
        }
        );
    });

// =============================================================================
// FALLBACK — Redirect ke dashboard jika route tidak ditemukan
// =============================================================================
Route::fallback(function () {
    return redirect()->route('dashboard');
});
