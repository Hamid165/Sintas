<?php
use Illuminate\Support\Facades\Route;

Route::get('/login', function() { return view('auth.login'); })->name('login');

// Admin panel - List pages
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');

    // Anak
    Route::get('/anak', fn() => view('admin.anak.index'))->name('admin.anak');
    Route::get('/anak/tambah', fn() => view('admin.anak.form'))->name('admin.anak.tambah');

    // Keuangan
    Route::get('/keuangan', fn() => view('admin.keuangan.index'))->name('admin.keuangan');
    Route::get('/keuangan/tambah', fn() => view('admin.keuangan.form'))->name('admin.keuangan.tambah');

    // Inventori
    Route::get('/inventori', fn() => view('admin.inventori.index'))->name('admin.inventori');
    Route::get('/inventori/tambah', fn() => view('admin.inventori.form'))->name('admin.inventori.tambah');

    // Kunjungan Tamu (menggantikan Artikel/CMS)
    Route::get('/kunjungan', fn() => view('admin.artikel.index'))->name('admin.kunjungan');
    Route::get('/kunjungan/tambah', fn() => view('admin.artikel.form'))->name('admin.kunjungan.tambah');

    // Profil
    Route::get('/profil', fn() => view('admin.profil'))->name('admin.profil');

    // Audit Sekretariat
    Route::get('/audit', fn() => view('admin.audit.index'))->name('admin.audit');
    Route::get('/audit/sekretariat', fn() => view('admin.audit.sekretariat.index'))->name('admin.audit.sekretariat');
    Route::get('/audit/keuangan', fn() => view('admin.audit.keuangan.index'))->name('admin.audit.keuangan');
});

// API Routes for Audit
Route::prefix('api')->group(function () {
    // Surat Masuk
    Route::get('/surat-masuk', [App\Http\Controllers\AuditSekreteriatController::class, 'getSuratMasuk']);
    Route::post('/surat-masuk', [App\Http\Controllers\AuditSekreteriatController::class, 'storeSuratMasuk']);
    Route::put('/surat-masuk/{suratMasuk}', [App\Http\Controllers\AuditSekreteriatController::class, 'updateSuratMasuk']);
    Route::delete('/surat-masuk/{suratMasuk}', [App\Http\Controllers\AuditSekreteriatController::class, 'destroySuratMasuk']);

    // Surat Keluar
    Route::get('/surat-keluar', [App\Http\Controllers\AuditSekreteriatController::class, 'getSuratKeluar']);
    Route::post('/surat-keluar', [App\Http\Controllers\AuditSekreteriatController::class, 'storeSuratKeluar']);
    Route::put('/surat-keluar/{suratKeluar}', [App\Http\Controllers\AuditSekreteriatController::class, 'updateSuratKeluar']);
    Route::delete('/surat-keluar/{suratKeluar}', [App\Http\Controllers\AuditSekreteriatController::class, 'destroySuratKeluar']);

    // Audit Keuangan
    Route::get('/audit-keuangan', [App\Http\Controllers\AuditKeuanganController::class, 'getAuditKeuangan']);
    Route::post('/audit-keuangan', [App\Http\Controllers\AuditKeuanganController::class, 'store']);
    Route::put('/audit-keuangan/{auditKeuangan}', [App\Http\Controllers\AuditKeuanganController::class, 'update']);
    Route::delete('/audit-keuangan/{auditKeuangan}', [App\Http\Controllers\AuditKeuanganController::class, 'destroy']);

    // Keuangan (for dropdown)
    Route::get('/keuangan', [App\Http\Controllers\KeuanganController::class, 'getAll']);

    // Export Routes
    Route::get('/export/surat-masuk-csv', [App\Http\Controllers\ExportController::class, 'suratMasukCsv']);
    Route::get('/export/surat-masuk-excel', [App\Http\Controllers\ExportController::class, 'suratMasukExcel']);
    Route::get('/export/surat-keluar-csv', [App\Http\Controllers\ExportController::class, 'suratKeluarCsv']);
    Route::get('/export/surat-keluar-excel', [App\Http\Controllers\ExportController::class, 'suratKeluarExcel']);
    Route::get('/export/audit-keuangan-csv', [App\Http\Controllers\ExportController::class, 'auditKeuanganCsv']);
    Route::get('/export/audit-keuangan-excel', [App\Http\Controllers\ExportController::class, 'auditKeuanganExcel']);

    // Kunjungan Tamu API
    Route::get('/kunjungan-tamu', [App\Http\Controllers\Api\KunjunganTamuController::class, 'index']);
    Route::post('/kunjungan-tamu', [App\Http\Controllers\Api\KunjunganTamuController::class, 'store']);
    Route::get('/kunjungan-tamu/surat-options', [App\Http\Controllers\Api\KunjunganTamuController::class, 'getSuratOptions']);
    Route::get('/kunjungan-tamu/{id}', [App\Http\Controllers\Api\KunjunganTamuController::class, 'show']);
    Route::post('/kunjungan-tamu/{id}', [App\Http\Controllers\Api\KunjunganTamuController::class, 'update']);
    Route::delete('/kunjungan-tamu/{id}', [App\Http\Controllers\Api\KunjunganTamuController::class, 'destroy']);
});

// Redirect root to dashboard
Route::get('/', fn() => redirect()->route('admin.dashboard'));
