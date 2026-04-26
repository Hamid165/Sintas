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

    public function strukturOrganisasi()
    {
        $users = User::orderByRaw("CASE WHEN role = 'admin' THEN 1 ELSE 2 END")
                     ->orderBy('id', 'desc')
                     ->get();
        return view('admin.sdm.struktur', compact('users'));
    }
    // Simpan Staf Baru
    public function simpanStaf(Request $request) {
        try {
            $request->validate([
                'name'      => 'required',
                'email'     => 'required|email|unique:users',
                'password'  => 'required|min:6',
                'role'      => 'required',
                'jabatan'   => 'required',
            ]);

            $user = User::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'password'       => Hash::make($request->password),
                'plain_password' => $request->password,
                'role'           => $request->role, // keep old string for fallback
                'jabatan'        => $request->jabatan,
            ]);

            // Assign Spatie RBAC Role
            $user->assignRole($request->role);

            return redirect()->to(route('admin.struktur') . '?toast=' . urlencode('Anggota berhasil ditambahkan!'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = implode(' ', array_merge(...array_values($e->errors())));
            return redirect()->to(route('admin.struktur') . '?toast=' . urlencode('Validasi gagal: ' . $errors) . '&toast_type=error');
        } catch (\Exception $e) {
            return redirect()->to(route('admin.struktur') . '?toast=' . urlencode('Terjadi kesalahan: ' . $e->getMessage()) . '&toast_type=error');
        }
    }

    public function hapusStaf($id) {
        $user = User::findOrFail($id);
        $namaUser = $user->name;
        User::where('parent_id', $id)->update(['parent_id' => null]);
        $user->delete();

        return redirect()->to(route('admin.struktur') . '?toast=' . urlencode($namaUser . ' berhasil dihapus dari struktur.'));
    }
}