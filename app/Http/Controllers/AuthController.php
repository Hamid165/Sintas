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

    public function showLupaPassword()
    {
        return view('auth.lupa-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Email tidak ditemukan!'], 404);
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        
        // Simpan OTP di session (berlaku selama session aktif / 15 menit)
        session(['reset_otp' => $otp, 'reset_email' => $user->email]);

        // Target nomor admin (Fonnte lebih stabil pakai format 628...)
        $target = '6283863053338';
        
        // Pesan WA
        $message = "Halo Admin,\n\nAda permintaan Reset Password untuk akun:\nEmail: *{$user->email}*\n\nBerikut adalah KODE OTP Anda: *{$otp}*\n\n_Abaikan jika Anda tidak merasa memintanya._";

        // Kirim via Fonnte API
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $message, 
                'delay' => '1',
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . env('FONNTE_TOKEN', 'TOKEN_FONNTE_ANDA')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return response()->json(['status' => 'error', 'message' => 'Sistem gagal menghubungi server WhatsApp.'], 500);
        }

        $resObj = json_decode($response);
        
        // Cek apakah Fonnte benar-benar sukses mengirim
        if (!$resObj || !isset($resObj->status) || $resObj->status !== true) {
            $reason = $resObj->reason ?? 'Perangkat WA Anda mungkin terputus / Token salah.';
            return response()->json(['status' => 'error', 'message' => 'Fonnte Error: ' . $reason], 400);
        }

        return response()->json(['status' => 'success', 'message' => 'OTP berhasil dikirim ke WhatsApp Admin!']);
    }

    public function showResetPassword()
    {
        if (!session('reset_email')) {
            return redirect()->route('lupa-password');
        }
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($request->otp != session('reset_otp')) {
            return response()->json(['status' => 'error', 'message' => 'Kode OTP salah atau kedaluwarsa!'], 400);
        }

        $user = User::where('email', session('reset_email'))->first();
        if ($user) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $user->plain_password = $request->password;
            $user->save();
            
            // Hapus session OTP
            session()->forget(['reset_otp', 'reset_email']);

            return response()->json(['status' => 'success', 'message' => 'Password berhasil diubah! Silakan login.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Gagal mereset password!'], 500);
    }
}