<?php

namespace App\Http\Controllers;

use App\Models\Anak; 
use App\Models\Keuangan; 
use App\Models\Inventaris;
use App\Models\AuditKeuangan; // Untuk Audit Keuangan
use App\Models\User; // Penting untuk Struktur Organisasi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAnak = Anak::count();
        
        $totalMasuk = Keuangan::where('jenis_transaksi', 'pemasukan')->sum('jumlah_nominal');
        $totalKeluar = Keuangan::where('jenis_transaksi', 'pengeluaran')->sum('jumlah_nominal');
        $saldoKas = $totalMasuk - $totalKeluar;

        $barangKritis = Inventaris::where('stok', '<', 10)->count();
        $transaksiTerakhir = Keuangan::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalAnak', 'saldoKas', 'barangKritis', 'transaksiTerakhir'
        ));
    }

    // NAMA METHOD INI HARUS SAMA DENGAN DI web.php
    public function strukturOrganisasi()
    {
        // Ambil atasan tertinggi (yang tidak punya parent)
$kepala = User::with('bawahan')->whereNull('parent_id')->first();

    return view('admin.sdm.struktur', compact('kepala'));
    }
    // Simpan Staf Baru
    public function simpanStaf(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required',
            'jabatan' => 'required',
            'parent_id' => 'nullable|exists:users,id'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'jabatan' => $request->jabatan,
            'parent_id' => $request->parent_id,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.struktur')->with('success', 'Anggota organisasi berhasil ditambahkan!');
    }

    // Hapus Staf
    public function hapusStaf($id) {
        $user = User::findOrFail($id);
        // Set bawahan menjadi tidak punya atasan dulu agar tidak error (optional)
        User::where('parent_id', $id)->update(['parent_id' => null]);
        $user->delete();

        return redirect()->route('admin.struktur')->with('success', 'Anggota berhasil dihapus.');
    }
}