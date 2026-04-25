<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi input (Sesuaikan dengan field di Blade kamu: email)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek ke Database (Auth::attempt otomatis nge-hash password)
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Buat session untuk web (agar dashboard bisa dibuka di browser)
            $request->session()->regenerate();

            // Buat token untuk API/Mobile (Sanctum)
            $token = $user->createToken('auth_token')->plainTextToken;

            // KIRIM JSON (Sesuai yang diminta JavaScript frontend)
            return response()->json([
                'status' => 'success',
                'message' => 'Login Berhasil',
                'token' => $token,
                'user' => [
                    'name' => $user->name,
                    'role' => $user->role,
                    'jabatan' => $user->jabatan
                ]
            ], 200);
        }

        // 3. Jika gagal, kirim JSON error 401
        return response()->json([
            'status' => 'error',
            'message' => 'Email atau Password salah!'
        ], 410);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}