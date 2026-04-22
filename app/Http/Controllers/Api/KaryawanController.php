<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    /**
     * Menampilkan daftar karyawan.
     */
    public function index()
    {
        $karyawan = User::where('role', 'karyawan')->latest()->get();
        return response()->json($karyawan);
    }

    /**
     * Menyimpan data karyawan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'jabatan'  => 'nullable|string|max:255',
            'no_hp'    => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'karyawan';

        $karyawan = User::create($validated);

        return response()->json([
            'message' => 'Data karyawan berhasil ditambahkan.',
            'data'    => $karyawan
        ], 201);
    }

    /**
     * Menampilkan detail karyawan.
     */
    public function show($id)
    {
        $karyawan = User::where('role', 'karyawan')->findOrFail($id);
        return response()->json($karyawan);
    }

    /**
     * Memperbarui data karyawan.
     */
    public function update(Request $request, $id)
    {
        $karyawan = User::where('role', 'karyawan')->findOrFail($id);

        $validated = $request->validate([
            'name'    => 'sometimes|required|string|max:255',
            'email'   => 'sometimes|required|email|unique:users,email,' . $id,
            'jabatan' => 'nullable|string|max:255',
            'no_hp'   => 'nullable|string|max:20',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $karyawan->update($validated);

        return response()->json([
            'message' => 'Data karyawan berhasil diperbarui.',
            'data'    => $karyawan
        ]);
    }

    /**
     * Menghapus karyawan.
     */
    public function destroy($id)
    {
        $karyawan = User::where('role', 'karyawan')->findOrFail($id);
        $karyawan->delete();

        return response()->json([
            'message' => 'Data karyawan berhasil dihapus.'
        ]);
    }
}