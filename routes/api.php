<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnakController;
use App\Http\Controllers\Api\InventarisController;
use App\Http\Controllers\Api\KeuanganController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Api\ArtikelController;
use App\Http\Controllers\Api\DashboardController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Landing page bisa narik data artikel & profil tanpa login
Route::get('/artikel', [ArtikelController::class, 'index']);
Route::get('/artikel/{id}', [ArtikelController::class, 'show']);
Route::get('/profil', [ProfilController::class, 'index']);

// Protected routes (Butuh Token Admin)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/update-profil', [AuthController::class, 'updateProfil']);
    
    // Dashboard Stats
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // API CRUD Lengkap
    Route::apiResource('anak', AnakController::class);
    Route::apiResource('inventaris', InventarisController::class);
    Route::apiResource('keuangan', KeuanganController::class);
    
    // Artikel sisa CRUD (Store, Update, Destroy)
    Route::post('/artikel', [ArtikelController::class, 'store']);
    Route::put('/artikel/{id}', [ArtikelController::class, 'update']);
    Route::delete('/artikel/{id}', [ArtikelController::class, 'destroy']);
    
    // Profil sisa CRUD
    Route::post('/profil', [ProfilController::class, 'store']);
    Route::put('/profil/{id}', [ProfilController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    // Get & update current user
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('/user/profile', function (Request $request) {
        $user = $request->user();
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto'  => 'nullable|image|max:10240', // 10MB max
            '_method' => 'nullable|string' // support spoofing
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                \Storage::disk('public')->delete($user->foto);
            }
            $path = $request->file('foto')->store('avatars', 'public');
            $data['foto'] = $path;
        }

        $user->update($data);
        return response()->json($user);
    });
    Route::put('/user/password', function (Request $request) {
        $user = $request->user();
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|min:8|confirmed',
        ]);
        if (!\Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password saat ini salah.'], 422);
        }
        $user->update(['password' => \Hash::make($request->password)]);
        return response()->json(['message' => 'Password berhasil diubah.']);
    });
});
