<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AuditSekreteriatController;
use App\Http\Controllers\AuditKeuanganController;
use App\Http\Controllers\Api\KeuanganController;
use App\Http\Controllers\Api\KunjunganTamuController;
use App\Http\Controllers\RoleController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/api/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Lupa Password Routes
Route::get('/lupa-password', [AuthController::class, 'showLupaPassword'])->name('lupa-password');
Route::post('/api/lupa-password/kirim-otp', [AuthController::class, 'sendOtp']);
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('reset-password');
Route::post('/api/reset-password', [AuthController::class, 'resetPassword']);

// Redirect root to dashboard
Route::get('/', fn() => redirect()->route('admin.dashboard'));

// Admin panel - List pages
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Global Access
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/profil', fn() => view('admin.profil'))->name('admin.profil');
    Route::get('/struktur-organisasi', [DashboardController::class, 'strukturOrganisasi'])->name('admin.struktur');

    // CRUD Struktur (Admin Only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/struktur/tambah', fn() => view('admin.sdm.tambah'))->name('admin.struktur.tambah');
        Route::post('/struktur/simpan', [DashboardController::class, 'simpanStaf'])->name('admin.struktur.simpan');
        Route::delete('/struktur/hapus/{id}', [DashboardController::class, 'hapusStaf'])->name('admin.struktur.hapus');
    });

    // Manajemen Hak Akses (Role & Permission)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('admin.role.index');
        Route::put('/roles/{id}', [RoleController::class, 'update'])->name('admin.role.update');
    });

    // Admin & Sekretariat
    Route::middleware(['role:admin,sekretariat'])->group(function () {
        Route::get('/anak', fn() => view('admin.anak.index'))->name('admin.anak');
        Route::get('/anak/tambah', fn() => view('admin.anak.form'))->name('admin.anak.tambah');
        Route::get('/kunjungan', fn() => view('admin.kunjungan.index'))->name('admin.kunjungan');
        Route::get('/kunjungan/tambah', fn() => view('admin.kunjungan.form'))->name('admin.kunjungan.tambah');
    });

    // Admin & Bendahara
    Route::middleware(['role:admin,bendahara'])->group(function () {
        Route::get('/keuangan', fn() => view('admin.keuangan.index'))->name('admin.keuangan');
        Route::get('/keuangan/tambah', fn() => view('admin.keuangan.form'))->name('admin.keuangan.tambah');
    });

    // Inventaris
    Route::middleware(['role:admin,karyawan'])->group(function () {
        Route::get('/inventori', fn() => view('admin.inventori.index'))->name('admin.inventori');
        Route::get('/inventori/tambah', fn() => view('admin.inventori.form'))->name('admin.inventori.tambah');
    });

    // Audit Menu
    Route::middleware(['role:admin,sekretariat,bendahara'])->group(function () {
        Route::get('/audit', fn() => view('admin.audit.index'))->name('admin.audit');
    });

    Route::middleware(['role:admin,bendahara'])->group(function () {
        Route::get('/audit/keuangan', [AuditKeuanganController::class, 'index'])->name('admin.audit.keuangan');
        Route::get('/audit/keuangan/tambah', [AuditKeuanganController::class, 'create'])->name('admin.audit.keuangan.tambah');
    });

    Route::middleware(['role:admin,sekretariat'])->group(function () {
        Route::get('/audit/sekretariat', fn() => view('admin.audit.sekretariat.index'))->name('admin.audit.sekretariat');
        Route::get('/audit/sekretariat/tambah-masuk', fn() => view('admin.audit.sekretariat.tambah-masuk'))->name('admin.audit.sekretariat.tambah-masuk');
        Route::get('/audit/sekretariat/tambah-keluar', fn() => view('admin.audit.sekretariat.tambah-keluar'))->name('admin.audit.sekretariat.tambah-keluar');
        Route::get('/audit/sekretariat/edit-masuk', fn() => view('admin.audit.sekretariat.tambah-masuk'))->name('admin.audit.sekretariat.edit-masuk');
        Route::get('/audit/sekretariat/edit-keluar', fn() => view('admin.audit.sekretariat.tambah-keluar'))->name('admin.audit.sekretariat.edit-keluar');
    });
});
