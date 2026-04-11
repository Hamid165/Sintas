<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\Inventaris;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAnak = Anak::count();
        $totalBarang = Inventaris::sum('stok');

        // Ini logika sederhana: Pemasukan - Pengeluaran = Saldo
        $pemasukan = Keuangan::where('jenis_transaksi', 'Pemasukan')->sum('jumlah_nominal');
        $pengeluaran = Keuangan::where('jenis_transaksi', 'Pengeluaran')->sum('jumlah_nominal');
        $totalSaldo = $pemasukan - $pengeluaran;

        return response()->json([
            'total_anak' => $totalAnak,
            'total_barang' => $totalBarang,
            'total_saldo' => $totalSaldo,
            'statistik_keuangan' => [
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran
            ]
        ]);
    }
}
