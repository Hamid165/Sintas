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

    // Artikel
    Route::get('/artikel', fn() => view('admin.artikel.index'))->name('admin.artikel');
    Route::get('/artikel/tambah', fn() => view('admin.artikel.form'))->name('admin.artikel.tambah');

    // Profil
    Route::get('/profil', fn() => view('admin.profil'))->name('admin.profil');
});

// Redirect root to dashboard
Route::get('/', fn() => redirect()->route('admin.dashboard'));
