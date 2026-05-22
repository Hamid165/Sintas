<?php
// Memicu sinkronisasi FTP ke server hosting

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required', // Bisa disesuaikan jadi username
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first(); // atau username

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Kredensial tidak valid'
            ], 401);
        }

        // Jika admin, berikan semua permission yang ada di tabel Permission
        if ($user->hasRole('admin') || $user->role === 'admin') {
            $permissions = \Spatie\Permission\Models\Permission::pluck('name');
        } else {
            $permissions = $user->getAllPermissions()->pluck('name');
        }

        $userArray = $user->toArray();
        $userArray['all_permissions'] = $permissions;

        return response()->json([
            'message' => 'Login sukses',
            'token' => $user->createToken('admin_token')->plainTextToken,
            'user' => $userArray
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout sukses'
        ]);
    }

    public function updateProfil(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profil berhasil diupdate',
            'user' => $user
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Password saat ini tidak cocok'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Password berhasil diubah'
        ]);
    }
}
