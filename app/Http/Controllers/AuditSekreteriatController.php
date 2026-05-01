<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\AuditKeuangan;
use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuditSekreteriatController extends Controller
{
    /**
     * Display the audit sekretariat page
     */
    public function index()
    {
        return view('admin.audit.sekretariat.index');
    }

    /**
     * Get surat masuk list with search and sort
     */
    public function getSuratMasuk(Request $request)
    {
        $query = SuratMasuk::query();

        // Search
        if ($search = $request->get('search')) {
            $query->where('kode_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%");
        }

        // Sort
        $sortBy = $request->get('sort', 'perihal');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sortBy, $direction);

        $perPage = min((int) $request->get('per_page', 10), 9999);
        $data = $query->paginate($perPage);
        return response()->json($data);
    }

    /**
     * Get single surat masuk
     */
    public function showSuratMasuk(SuratMasuk $suratMasuk)
    {
        return response()->json($suratMasuk);
    }

    /**
     * Get surat keluar list with search and sort
     */
    public function getSuratKeluar(Request $request)
    {
        $query = SuratKeluar::query();

        // Search
        if ($search = $request->get('search')) {
            $query->where('kode_surat', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%");
        }

        // Sort
        $sortBy = $request->get('sort', 'perihal');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sortBy, $direction);

        $perPage = min((int) $request->get('per_page', 10), 9999);
        $data = $query->paginate($perPage);
        return response()->json($data);
    }

    /**
     * Get single surat keluar
     */
    public function showSuratKeluar(SuratKeluar $suratKeluar)
    {
        return response()->json($suratKeluar);
    }

    /**
     * Store a newly created surat masuk
     */
    public function storeSuratMasuk(Request $request)
    {
        $validated = $request->validate([
            'kode_surat' => 'required|unique:surat_masuks',
            'perihal' => 'required|string',
            'pengirim' => 'required|string',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $surat = SuratMasuk::create($validated);
        broadcast(new \App\Events\SuratMasukUpdated($surat, 'create'));
        return response()->json(['message' => 'Surat masuk berhasil ditambahkan', 'data' => $surat], 201);
    }

    /**
     * Store a newly created surat keluar
     */
    public function storeSuratKeluar(Request $request)
    {
        $validated = $request->validate([
            'kode_surat' => 'required|unique:surat_keluars',
            'perihal' => 'required|string',
            'tujuan' => 'required|string',
            'tanggal_surat' => 'required|date',
            'tanggal_dikirim' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $surat = SuratKeluar::create($validated);
        broadcast(new \App\Events\SuratKeluarUpdated($surat, 'create'));
        return response()->json(['message' => 'Surat keluar berhasil ditambahkan', 'data' => $surat], 201);
    }

    /**
     * Update the specified surat masuk
     */
    public function updateSuratMasuk(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'perihal' => 'required|string',
            'pengirim' => 'required|string',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $suratMasuk->update($validated);
        broadcast(new \App\Events\SuratMasukUpdated($suratMasuk, 'update'));
        return response()->json(['message' => 'Surat masuk berhasil diperbarui', 'data' => $suratMasuk]);
    }

    /**
     * Update the specified surat keluar
     */
    public function updateSuratKeluar(Request $request, SuratKeluar $suratKeluar)
    {
        $validated = $request->validate([
            'perihal' => 'required|string',
            'tujuan' => 'required|string',
            'tanggal_surat' => 'required|date',
            'tanggal_dikirim' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $suratKeluar->update($validated);
        broadcast(new \App\Events\SuratKeluarUpdated($suratKeluar, 'update'));
        return response()->json(['message' => 'Surat keluar berhasil diperbarui', 'data' => $suratKeluar]);
    }

    /**
     * Delete the specified surat masuk
     */
    public function destroySuratMasuk(SuratMasuk $suratMasuk)
    {
        $suratMasuk->delete();
        broadcast(new \App\Events\SuratMasukUpdated($suratMasuk, 'delete'));
        return response()->json(['message' => 'Surat masuk berhasil dihapus']);
    }

    /**
     * Delete the specified surat keluar
     */
    public function destroySuratKeluar(SuratKeluar $suratKeluar)
    {
        $suratKeluar->delete();
        broadcast(new \App\Events\SuratKeluarUpdated($suratKeluar, 'delete'));
        return response()->json(['message' => 'Surat keluar berhasil dihapus']);
    }
}
