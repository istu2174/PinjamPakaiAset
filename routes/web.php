<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Pengelola;
use App\Http\Controllers\Peminjam;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — SIPANDA
|--------------------------------------------------------------------------
|
| Struktur route dikelompokkan berdasarkan role:
|   - Auth     : login, register, logout — dihandle oleh routes/auth.php (Breeze)
|   - Profile  : /profile (Breeze)
|   - Peminjam : /peminjam/*
|   - Pengelola: /pengelola/*
|   - Admin    : /admin/*
|   - Surat    : /surat/* (scan barcode — auth-gated)
|
*/

// Redirect root ke login
Route::get('/', fn () => redirect()->route('login'));

// =============================================================================
// Profile Routes (Breeze)
// =============================================================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =============================================================================
// Notifikasi Routes (semua role yang login)
// =============================================================================

Route::middleware('auth')->prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/', [NotifikasiController::class, 'index'])->name('index');
    Route::get('/unread-count', [NotifikasiController::class, 'unreadCount'])->name('unread-count');
    Route::post('/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('mark-read');
    Route::post('/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('read-all');
});

// =============================================================================
// Surat Scan Route (auth-gated barcode)
// =============================================================================

Route::middleware('auth')
    ->get('/surat/scan/{token}', [Peminjam\PeminjamanController::class, 'scanBarcode'])
    ->name('surat.scan');

// Lihat / unduh PDF surat pernyataan (disajikan via controller, akses dicek per role)
Route::middleware('auth')
    ->get('/surat/peminjaman/{peminjaman}/pdf', [\App\Http\Controllers\SuratController::class, 'show'])
    ->name('surat.pdf');

// Lab Simulasi & Komparasi Algoritma
Route::middleware('auth')
    ->get('/simulasi-algoritma', [\App\Http\Controllers\SimulasiAlgoritmaController::class, 'index'])
    ->name('simulasi.algoritma');

// =============================================================================
// Peminjam Routes
// =============================================================================

Route::middleware(['auth', 'role:peminjam'])
    ->prefix('peminjam')
    ->name('peminjam.')
    ->group(function () {
        // Halaman utama (search aset) — landing page setelah login
        Route::get('/cari', [Peminjam\AsetSearchController::class, 'index'])->name('cari');

        // Dashboard
        Route::get('/dashboard', [Peminjam\DashboardController::class, 'index'])
            ->name('dashboard');

        // Peminjaman
        Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
            Route::get('/', [Peminjam\PeminjamanController::class, 'index'])->name('index');
            Route::get('/buat', [Peminjam\PeminjamanController::class, 'create'])->name('create');
            Route::post('/', [Peminjam\PeminjamanController::class, 'store'])->name('store');
            Route::get('/{peminjaman}', [Peminjam\PeminjamanController::class, 'show'])->name('show');
            Route::post('/{peminjaman}/batalkan', [Peminjam\PeminjamanController::class, 'batalkan'])->name('batalkan');
            Route::get('/{peminjaman}/surat/download', [Peminjam\PeminjamanController::class, 'downloadSurat'])->name('surat.download');
        });

        // Keluhan
        Route::prefix('keluhan')->name('keluhan.')->group(function () {
            Route::get('/', [Peminjam\KeluhanController::class, 'index'])->name('index');
            Route::get('/buat', [Peminjam\KeluhanController::class, 'create'])->name('create');
            Route::post('/', [Peminjam\KeluhanController::class, 'store'])->name('store');
            Route::get('/{keluhan}', [Peminjam\KeluhanController::class, 'show'])->name('show');
        });
    });

// =============================================================================
// Pengelola Routes
// =============================================================================

Route::middleware(['auth', 'role:pengelola'])
    ->prefix('pengelola')
    ->name('pengelola.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [Pengelola\DashboardController::class, 'index'])
            ->name('dashboard');

        // Peminjaman
        Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
            Route::get('/', [Pengelola\PeminjamanController::class, 'index'])->name('index');
            Route::get('/{peminjaman}', [Pengelola\PeminjamanController::class, 'show'])->name('show');
            Route::post('/{peminjaman}/setujui', [Pengelola\PeminjamanController::class, 'setujui'])->name('setujui');
            Route::post('/{peminjaman}/tolak', [Pengelola\PeminjamanController::class, 'tolak'])->name('tolak');
            Route::post('/{peminjaman}/surat', [Pengelola\PeminjamanController::class, 'generateSurat'])->name('surat.generate');
            Route::post('/{peminjaman}/konfirmasi-ambil', [Pengelola\PeminjamanController::class, 'konfirmasiAmbil'])->name('konfirmasi-ambil');
            Route::post('/{peminjaman}/konfirmasi-kembali', [Pengelola\PeminjamanController::class, 'konfirmasiKembali'])->name('konfirmasi-kembali');
        });

        // Aset (lihat & penilaian Decision Tree)
        Route::prefix('aset')->name('aset.')->group(function () {
            Route::get('/', [Pengelola\AsetController::class, 'index'])->name('index');
            Route::get('/{aset}/penilaian', [Pengelola\AsetController::class, 'formPenilaian'])->name('penilaian');
            Route::post('/{aset}/penilaian', [Pengelola\AsetController::class, 'simpanPenilaian'])->name('penilaian.simpan');
        });

        // Keluhan
        Route::prefix('keluhan')->name('keluhan.')->group(function () {
            Route::get('/', [Pengelola\KeluhanController::class, 'index'])->name('index');
            Route::get('/{keluhan}', [Pengelola\KeluhanController::class, 'show'])->name('show');
            Route::post('/{keluhan}/respon', [Pengelola\KeluhanController::class, 'respon'])->name('respon');
        });
    });

// =============================================================================
// Admin Routes
// =============================================================================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [Admin\UserController::class, 'index'])->name('index');
            Route::get('/buat', [Admin\UserController::class, 'create'])->name('create');
            Route::post('/', [Admin\UserController::class, 'store'])->name('store');
            Route::get('/{user}', [Admin\UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [Admin\UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [Admin\UserController::class, 'update'])->name('update');
            Route::post('/{user}/toggle-aktif', [Admin\UserController::class, 'toggleAktif'])->name('toggle-aktif');
            Route::post('/{user}/toggle-rekap', [Admin\UserController::class, 'toggleRekap'])->name('toggle-rekap');
        });

        // Aset Management
        Route::prefix('aset')->name('aset.')->group(function () {
            Route::get('/', [Admin\AsetController::class, 'index'])->name('index');
            Route::get('/buat', [Admin\AsetController::class, 'create'])->name('create');
            Route::post('/', [Admin\AsetController::class, 'store'])->name('store');
            Route::get('/{aset}', [Admin\AsetController::class, 'show'])->name('show');
            Route::get('/{aset}/edit', [Admin\AsetController::class, 'edit'])->name('edit');
            Route::put('/{aset}', [Admin\AsetController::class, 'update'])->name('update');
            Route::delete('/{aset}', [Admin\AsetController::class, 'destroy'])->name('destroy');

            // Kategori Aset
            Route::get('/kategori/daftar', [Admin\AsetController::class, 'indexKategori'])->name('kategori.index');
            Route::post('/kategori', [Admin\AsetController::class, 'storeKategori'])->name('kategori.store');
            Route::put('/kategori/{kategori}', [Admin\AsetController::class, 'updateKategori'])->name('kategori.update');
            Route::delete('/kategori/{kategori}', [Admin\AsetController::class, 'destroyKategori'])->name('kategori.destroy');
        });

        // Peminjaman (hanya monitoring — persetujuan/penolakan sepenuhnya oleh Pengelola Aset)
        Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
            Route::get('/', [Admin\PeminjamanController::class, 'index'])->name('index');
            Route::get('/{peminjaman}', [Admin\PeminjamanController::class, 'show'])->name('show');
        });

        // Laporan & Export
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [Admin\ReportController::class, 'index'])->name('index');
            Route::get('/peminjaman/pdf', [Admin\ReportController::class, 'exportPeminjamanPdf'])->name('peminjaman.pdf');
            Route::get('/aset/pdf', [Admin\ReportController::class, 'exportAsetPdf'])->name('aset.pdf');
            Route::get('/peminjam/excel', [Admin\ReportController::class, 'exportPeminjamExcel'])->name('peminjam.excel');
        });
    });

// =============================================================================
// Breeze Auth Routes
// =============================================================================

require __DIR__ . '/auth.php';
