<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use App\Models\Inventaris;
use App\Models\Keuangan;
use App\Models\KunjunganTamu;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\AuditKeuangan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // === STATS ===
        $totalAnak      = Anak::count();
        $totalBarang    = Inventaris::sum('stok');
        $totalItem      = Inventaris::count();
        $barangKritis   = Inventaris::where('stok', '<', 10)->count();

        $pemasukan      = Keuangan::where('jenis_transaksi', 'Pemasukan')->sum('jumlah_nominal');
        $pengeluaran    = Keuangan::where('jenis_transaksi', 'Pengeluaran')->sum('jumlah_nominal');
        $totalSaldo     = $pemasukan - $pengeluaran;
        $totalTransaksi = Keuangan::count();

        $totalKunjungan     = KunjunganTamu::count();
        $kunjunganBulanIni  = KunjunganTamu::whereRaw('MONTH(tanggal_pelaksanaan) = ? AND YEAR(tanggal_pelaksanaan) = ?', [now()->month, now()->year])->count();

        $totalSuratMasuk  = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();
        $totalAudit       = AuditKeuangan::count();

        // === RECENT DATA ===
        $recentAnak = Anak::latest()->take(5)->get(['id','nama_lengkap','jenis_kelamin','usia','tempat_tgl_lahir','created_at']);

        $recentKeuangan = Keuangan::latest()->take(5)->get(['id','jenis_transaksi','kategori','keterangan','jumlah_nominal','created_at']);

        $recentInventaris = Inventaris::latest()->take(5)->get(['id','nama_barang','kategori','stok','kondisi','created_at']);

        $recentKunjungan = KunjunganTamu::latest()->take(5)->get(['id','nama_tamu','judul_kegiatan','tanggal_pelaksanaan']);

        $recentSuratMasuk = SuratMasuk::latest()->take(5)->get(['id','kode_surat','perihal','pengirim','tanggal_surat']);

        $recentSuratKeluar = SuratKeluar::latest()->take(5)->get(['id','kode_surat','perihal','tujuan','tanggal_surat']);

        return response()->json([
            // Stats
            'total_anak'          => $totalAnak,
            'total_barang'        => $totalBarang,
            'total_item'          => $totalItem,
            'barang_kritis'       => $barangKritis,
            'total_saldo'         => $totalSaldo,
            'total_transaksi'     => $totalTransaksi,
            'pemasukan'           => $pemasukan,
            'pengeluaran'         => $pengeluaran,
            'total_kunjungan'     => $totalKunjungan,
            'kunjungan_bulan_ini' => $kunjunganBulanIni,
            'total_surat_masuk'   => $totalSuratMasuk,
            'total_surat_keluar'  => $totalSuratKeluar,
            'total_audit'         => $totalAudit,

            // Recent
            'recent_anak'         => $recentAnak,
            'recent_keuangan'     => $recentKeuangan,
            'recent_inventaris'   => $recentInventaris,
            'recent_kunjungan'    => $recentKunjungan,
            'recent_surat_masuk'  => $recentSuratMasuk,
            'recent_surat_keluar' => $recentSuratKeluar,
        ]);
    }
}
