<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // Prototype sederhana sesuai kode React Anda (admin/admin123)
        // Nantinya ini bisa diganti dengan pengecekan database
        if ($request->username == 'admin' && $request->password == 'admin123') {
            session(['isLoggedIn' => true]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['loginError' => 'Username atau Password salah!']);
    }

    public function logout()
    {
        session()->forget('isLoggedIn');
        return redirect()->route('login');
    }
}