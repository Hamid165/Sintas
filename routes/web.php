<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AuditSekreteriatController;
use App\Http\Controllers\AuditKeuanganController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\Api\KunjunganTamuController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/api/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect root to dashboard
Route::get('/', fn() => redirect()->route('admin.dashboard'));

// Admin panel - List pages
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    // Global Access
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/profil', fn() => view('admin.profil'))->name('admin.profil');
    Route::get('/struktur-organisasi', [DashboardController::class, 'strukturOrganisasi'])->name('admin.struktur');

    // CRUD Struktur (Admin Only)
    Route::get('/struktur/tambah', [DashboardController::class, 'tambahStaf'])->name('admin.struktur.tambah');
    Route::post('/struktur/simpan', [DashboardController::class, 'simpanStaf'])->name('admin.struktur.simpan');
    Route::get('/struktur/edit/{id}', [DashboardController::class, 'editStaf'])->name('admin.struktur.edit');
    Route::post('/struktur/update/{id}', [DashboardController::class, 'updateStaf'])->name('admin.struktur.update');
    Route::delete('/struktur/hapus/{id}', [DashboardController::class, 'hapusStaf'])->name('admin.struktur.hapus');

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
    Route::middleware(['role:admin,karyawan,bendahara'])->group(function () {
        Route::get('/inventori', fn() => view('admin.inventori.index'))->name('admin.inventori');
        Route::get('/inventori/tambah', fn() => view('admin.inventori.form'))->name('admin.inventori.tambah');
    });

    // Audit Menu
    Route::middleware(['role:admin,sekretariat'])->group(function () {
        Route::get('/audit', fn() => view('admin.audit.index'))->name('admin.audit');
        Route::get('/audit/keuangan', [AuditKeuanganController::class, 'index'])->name('admin.audit.keuangan');
        Route::get('/audit/sekretariat', fn() => view('admin.audit.sekretariat.index'))->name('admin.audit.sekretariat');
    });
});

// API Routes
Route::prefix('api')->middleware(['auth'])->group(function () {
    
    // Surat
    Route::get('/surat-masuk', [AuditSekreteriatController::class, 'getSuratMasuk']);
    Route::post('/surat-masuk', [AuditSekreteriatController::class, 'storeSuratMasuk']);
    Route::put('/surat-masuk/{suratMasuk}', [AuditSekreteriatController::class, 'updateSuratMasuk']);
    Route::delete('/surat-masuk/{suratMasuk}', [AuditSekreteriatController::class, 'destroySuratMasuk']);

    Route::get('/surat-keluar', [AuditSekreteriatController::class, 'getSuratKeluar']);
    Route::post('/surat-keluar', [AuditSekreteriatController::class, 'storeSuratKeluar']);
    Route::put('/surat-keluar/{suratKeluar}', [AuditSekreteriatController::class, 'updateSuratKeluar']);
    Route::delete('/surat-keluar/{suratKeluar}', [AuditSekreteriatController::class, 'destroySuratKeluar']);

    // Audit Keuangan
    Route::get('/audit-keuangan', [AuditKeuanganController::class, 'getAuditKeuangan']);
    Route::post('/audit-keuangan', [AuditKeuanganController::class, 'store']);
    Route::put('/audit-keuangan/{auditKeuangan}', [AuditKeuanganController::class, 'update']);
    Route::delete('/audit-keuangan/{auditKeuangan}', [AuditKeuanganController::class, 'destroy']);

    Route::get('/keuangan-list', [KeuanganController::class, 'getAll']);

    // Export
    Route::controller(ExportController::class)->group(function () {
        Route::get('/export/surat-masuk-csv', 'suratMasukCsv');
        Route::get('/export/surat-masuk-excel', 'suratMasukExcel');
        Route::get('/export/surat-keluar-csv', 'suratKeluarCsv');
        Route::get('/export/surat-keluar-excel', 'suratKeluarExcel');
        Route::get('/export/audit-keuangan-csv', 'auditKeuanganCsv');
        Route::get('/export/audit-keuangan-excel', 'auditKeuanganExcel');
    });

    // Kunjungan
    Route::controller(KunjunganTamuController::class)->group(function () {
        Route::get('/kunjungan-tamu', 'index');
        Route::post('/kunjungan-tamu', 'store');
        Route::get('/kunjungan-tamu/surat-options', 'getSuratOptions');
        Route::get('/kunjungan-tamu/{id}', 'show');
        Route::post('/kunjungan-tamu/{id}', 'update');
        Route::delete('/kunjungan-tamu/{id}', 'destroy');
    });
});