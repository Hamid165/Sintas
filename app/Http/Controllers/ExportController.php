<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\AuditKeuangan;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export surat masuk to CSV
     */
    public function suratMasukCsv()
    {
        $data = SuratMasuk::orderBy('tanggal_diterima', 'desc')->get();
        
        $filename = 'surat_masuk_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // CSV Header
        fputcsv($handle, ['Kode Surat', 'Perihal', 'Pengirim', 'Tanggal Surat', 'Tanggal Diterima', 'Keterangan']);

        // CSV Data
        foreach ($data as $row) {
            fputcsv($handle, [
                $row->kode_surat,
                $row->perihal,
                $row->pengirim,
                $row->tanggal_surat->format('d-m-Y'),
                $row->tanggal_diterima->format('d-m-Y'),
                $row->keterangan,
            ]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Export surat keluar to CSV
     */
    public function suratKeluarCsv()
    {
        $data = SuratKeluar::orderBy('tanggal_dikirim', 'desc')->get();
        
        $filename = 'surat_keluar_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // CSV Header
        fputcsv($handle, ['Kode Surat', 'Perihal', 'Tujuan', 'Tanggal Surat', 'Tanggal Dikirim', 'Keterangan']);

        // CSV Data
        foreach ($data as $row) {
            fputcsv($handle, [
                $row->kode_surat,
                $row->perihal,
                $row->tujuan,
                $row->tanggal_surat->format('d-m-Y'),
                $row->tanggal_dikirim->format('d-m-Y'),
                $row->keterangan,
            ]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Export audit keuangan to CSV
     */
    public function auditKeuanganCsv()
    {
        $data = AuditKeuangan::with('keuangan')->orderBy('created_at', 'desc')->get();
        
        $filename = 'audit_keuangan_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        // CSV Header
        fputcsv($handle, ['Tanggal Audit', 'Jenis Audit', 'Kode Dokumen', 'Keterangan', 'Nominal (Rp)', 'Jenis Transaksi']);

        // CSV Data
        foreach ($data as $row) {
            fputcsv($handle, [
                $row->created_at->format('d-m-Y H:i'),
                $row->jenis_audit,
                $row->kode_dokumen,
                $row->keterangan,
                number_format($row->keuangan->jumlah_nominal ?? 0, 0, ',', '.'),
                $row->keuangan->jenis_transaksi ?? 'PENGELUARAN',
            ]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Export surat masuk to Excel
     */
    public function suratMasukExcel()
    {
        $data = SuratMasuk::orderBy('tanggal_diterima', 'desc')->get();
        
        // Generate HTML table
        $html = '<table border="1" cellpadding="10">';
        $html .= '<tr style="background-color: #4CAF50; color: white; font-weight: bold;">';
        $html .= '<td>Kode Surat</td>';
        $html .= '<td>Perihal</td>';
        $html .= '<td>Pengirim</td>';
        $html .= '<td>Tanggal Surat</td>';
        $html .= '<td>Tanggal Diterima</td>';
        $html .= '<td>Keterangan</td>';
        $html .= '</tr>';

        foreach ($data as $row) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row->kode_surat) . '</td>';
            $html .= '<td>' . htmlspecialchars($row->perihal) . '</td>';
            $html .= '<td>' . htmlspecialchars($row->pengirim) . '</td>';
            $html .= '<td>' . $row->tanggal_surat->format('d-m-Y') . '</td>';
            $html .= '<td>' . $row->tanggal_diterima->format('d-m-Y') . '</td>';
            $html .= '<td>' . htmlspecialchars($row->keterangan ?? '-') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        $filename = 'surat_masuk_' . date('Y-m-d_H-i-s') . '.xls';
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo $html;
        exit;
    }

    /**
     * Export surat keluar to Excel
     */
    public function suratKeluarExcel()
    {
        $data = SuratKeluar::orderBy('tanggal_dikirim', 'desc')->get();
        
        $html = '<table border="1" cellpadding="10">';
        $html .= '<tr style="background-color: #2196F3; color: white; font-weight: bold;">';
        $html .= '<td>Kode Surat</td>';
        $html .= '<td>Perihal</td>';
        $html .= '<td>Tujuan</td>';
        $html .= '<td>Tanggal Surat</td>';
        $html .= '<td>Tanggal Dikirim</td>';
        $html .= '<td>Keterangan</td>';
        $html .= '</tr>';

        foreach ($data as $row) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row->kode_surat) . '</td>';
            $html .= '<td>' . htmlspecialchars($row->perihal) . '</td>';
            $html .= '<td>' . htmlspecialchars($row->tujuan) . '</td>';
            $html .= '<td>' . $row->tanggal_surat->format('d-m-Y') . '</td>';
            $html .= '<td>' . $row->tanggal_dikirim->format('d-m-Y') . '</td>';
            $html .= '<td>' . htmlspecialchars($row->keterangan ?? '-') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        $filename = 'surat_keluar_' . date('Y-m-d_H-i-s') . '.xls';
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo $html;
        exit;
    }

    /**
     * Export audit keuangan to Excel
     */
    public function auditKeuanganExcel()
    {
        $data = AuditKeuangan::with('keuangan')->orderBy('created_at', 'desc')->get();
        
        $html = '<table border="1" cellpadding="10">';
        $html .= '<tr style="background-color: #FF9800; color: white; font-weight: bold;">';
        $html .= '<td>Tanggal Audit</td>';
        $html .= '<td>Jenis Audit</td>';
        $html .= '<td>Kode Dokumen</td>';
        $html .= '<td>Keterangan</td>';
        $html .= '<td>Nominal (Rp)</td>';
        $html .= '<td>Jenis Transaksi</td>';
        $html .= '</tr>';

        foreach ($data as $row) {
            $html .= '<tr>';
            $html .= '<td>' . $row->created_at->format('d-m-Y H:i') . '</td>';
            $html .= '<td>' . $row->jenis_audit . '</td>';
            $html .= '<td>' . htmlspecialchars($row->kode_dokumen) . '</td>';
            $html .= '<td>' . htmlspecialchars($row->keterangan ?? '-') . '</td>';
            $html .= '<td>' . number_format($row->keuangan->jumlah_nominal ?? 0, 0, ',', '.') . '</td>';
            $html .= '<td>' . ($row->keuangan->jenis_transaksi ?? 'PENGELUARAN') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        $filename = 'audit_keuangan_' . date('Y-m-d_H-i-s') . '.xls';
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        echo $html;
        exit;
    }
}
