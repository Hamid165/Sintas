<?php

namespace App\Http\Controllers;

use App\Models\AuditKeuangan;
use App\Models\Keuangan;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;

class AuditKeuanganController extends Controller
{
    /**
     * Display the audit keuangan page
     */
    public function index()
    {
        return view('admin.audit.keuangan.index');
    }

    /**
     * Get audit keuangan list with search and sort
     */
    public function getAuditKeuangan(Request $request)
    {
        $query = AuditKeuangan::with('keuangan')->orderBy('created_at', 'desc');

        // Search
        if ($search = $request->get('search')) {
            $query->where('kode_dokumen', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('keuangan', function ($q) use ($search) {
                      $q->where('keterangan', 'like', "%{$search}%");
                  });
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        
        if ($sortBy === 'tanggal') {
            $query->orderBy('created_at', $direction);
        } else {
            $query->orderBy($sortBy, $direction);
        }

        $perPage = min((int) $request->get('per_page', 10), 9999);
        $data = $query->paginate($perPage);

        // Format response
        $data->transform(function ($item) {
            return [
                'id' => $item->id,
                'keuangan_id' => $item->keuangan_id,
                'tanggal' => $item->keuangan->created_at ?? $item->created_at,
                'jenis' => $item->jenis_audit,
                'kode_dokumen' => $item->kode_dokumen,
                'keterangan' => $item->keuangan->keterangan ?? $item->keterangan,
                'nominal' => $item->keuangan->jumlah_nominal ?? 0,
                'keuangan_jenis' => $item->keuangan->jenis_transaksi ?? 'PENGELUARAN',
            ];
        });

        return response()->json($data);
    }

    /**
     * Store a newly created audit keuangan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'keuangan_id' => 'required|exists:keuangans,id',
            'jenis_audit' => 'required|in:MASUK,KELUAR',
            'kode_dokumen' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        // Check if kode_dokumen is a valid letter code
        $kode = $validated['kode_dokumen'];
        $isValidCode = false;

        if (strpos($kode, 'SRT-IN-') === 0) {
            $isValidCode = SuratMasuk::where('kode_surat', $kode)->exists();
            $validated['kode_surat'] = $kode;
        } elseif (strpos($kode, 'SRT-OUT-') === 0) {
            $isValidCode = SuratKeluar::where('kode_surat', $kode)->exists();
            $validated['kode_surat'] = $kode;
        }

        if (!$isValidCode) {
            return response()->json(['message' => 'Kode dokumen tidak ditemukan'], 422);
        }

        $audit = AuditKeuangan::create($validated);
        return response()->json(['message' => 'Audit keuangan berhasil ditambahkan', 'data' => $audit], 201);
    }

    /**
     * Update the specified audit keuangan
     */
    public function update(Request $request, AuditKeuangan $auditKeuangan)
    {
        $validated = $request->validate([
            'jenis_audit' => 'required|in:MASUK,KELUAR',
            'kode_dokumen' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        // Check if kode_dokumen is a valid letter code
        $kode = $validated['kode_dokumen'];
        $isValidCode = false;

        if (strpos($kode, 'SRT-IN-') === 0) {
            $isValidCode = SuratMasuk::where('kode_surat', $kode)->exists();
            $validated['kode_surat'] = $kode;
        } elseif (strpos($kode, 'SRT-OUT-') === 0) {
            $isValidCode = SuratKeluar::where('kode_surat', $kode)->exists();
            $validated['kode_surat'] = $kode;
        }

        if (!$isValidCode) {
            return response()->json(['message' => 'Kode dokumen tidak ditemukan'], 422);
        }

        $auditKeuangan->update($validated);
        return response()->json(['message' => 'Audit keuangan berhasil diperbarui', 'data' => $auditKeuangan]);
    }

    /**
     * Delete the specified audit keuangan
     */
    public function destroy(AuditKeuangan $auditKeuangan)
    {
        $auditKeuangan->delete();
        return response()->json(['message' => 'Audit keuangan berhasil dihapus']);
    }
}
