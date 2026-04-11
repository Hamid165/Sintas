<?php

namespace App\Http\Controllers;

use App\Models\AnakAsuh;
use App\Models\Transaksi;
use App\Models\Inventori;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Total Anak
        $totalAnak = AnakAsuh::count();

        // 2. Hitung Saldo Kas SINTAS
        $totalMasuk = Transaksi::where('tipe', 'masuk')->sum('nominal');
        $totalKeluar = Transaksi::where('tipe', 'keluar')->sum('nominal');
        $saldoKas = $totalMasuk - $totalKeluar;

        // 3. Hitung Barang yang Hampir Habis (Contoh: Stok < 10)
        $barangKritis = Inventori::where('stok', '<', 10)->count();

        // 4. Ambil 5 Transaksi Terakhir untuk Aktivitas Terbaru
        $transaksiTerakhir = Transaksi::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalAnak', 
            'saldoKas', 
            'barangKritis', 
            'transaksiTerakhir'
        ));
    }
}