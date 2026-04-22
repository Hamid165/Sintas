<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KunjunganTamu;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KunjunganTamuController extends Controller
{
    /**
     * Return all kunjungan tamu records, latest first.
     */
    public function index()
    {
        $data = KunjunganTamu::latest()->get()->map(function ($item) {
            return [
                'id'                  => $item->id,
                'judul_kegiatan'      => $item->judul_kegiatan,
                'nama_tamu'           => $item->nama_tamu,
                'tanggal_pelaksanaan' => $item->tanggal_pelaksanaan?->format('Y-m-d'),
                'foto_url'            => $item->foto_url,
                'deskripsi_laporan'   => $item->deskripsi_laporan,
                'nomor_surat_ref'     => $item->nomor_surat_ref,
                'created_at'          => $item->created_at,
            ];
        });

        return response()->json($data);
    }

    /**
     * Store a newly created kunjungan tamu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_kegiatan'      => 'required|string|max:255',
            'nama_tamu'           => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'required|date',
            'foto_kegiatan'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'deskripsi_laporan'   => 'required|string',
            'nomor_surat_ref'     => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('foto_kegiatan')) {
            $validated['foto_kegiatan'] = $request->file('foto_kegiatan')
                ->store('kunjungan', 'public');
        }

        $kunjungan = KunjunganTamu::create($validated);
        return response()->json([
            'message' => 'Data kunjungan tamu berhasil ditambahkan.',
            'data'    => $kunjungan,
        ], 201);
    }

    /**
     * Return a single record.
     */
    public function show($id)
    {
        $item = KunjunganTamu::findOrFail($id);
        return response()->json([
            'id'                  => $item->id,
            'judul_kegiatan'      => $item->judul_kegiatan,
            'nama_tamu'           => $item->nama_tamu,
            'tanggal_pelaksanaan' => $item->tanggal_pelaksanaan?->format('Y-m-d'),
            'foto_url'            => $item->foto_url,
            'deskripsi_laporan'   => $item->deskripsi_laporan,
            'nomor_surat_ref'     => $item->nomor_surat_ref,
        ]);
    }

    /**
     * Update a kunjungan tamu record.
     */
    public function update(Request $request, $id)
    {
        $item = KunjunganTamu::findOrFail($id);

        $data = $request->validate([
            'judul_kegiatan'      => 'sometimes|required|string|max:255',
            'nama_tamu'           => 'sometimes|required|string|max:255',
            'tanggal_pelaksanaan' => 'sometimes|required|date',
            'foto_kegiatan'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'deskripsi_laporan'   => 'sometimes|required|string',
            'nomor_surat_ref'     => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('foto_kegiatan')) {
            // Remove old photo
            if ($item->foto_kegiatan && Storage::disk('public')->exists($item->foto_kegiatan)) {
                Storage::disk('public')->delete($item->foto_kegiatan);
            }
            $data['foto_kegiatan'] = $request->file('foto_kegiatan')
                ->store('kunjungan', 'public');
        }

        $item->update($data);
        return response()->json(['message' => 'Data berhasil diperbarui.', 'data' => $item]);
    }

    /**
     * Delete a kunjungan tamu record.
     */
    public function destroy($id)
    {
        $item = KunjunganTamu::findOrFail($id);

        if ($item->foto_kegiatan && Storage::disk('public')->exists($item->foto_kegiatan)) {
            Storage::disk('public')->delete($item->foto_kegiatan);
        }

        $item->delete();
        return response()->json(['message' => 'Data kunjungan tamu berhasil dihapus.']);
    }

    /**
     * Return all surat (masuk + keluar) for reference dropdown.
     */
    public function getSuratOptions()
    {
        $masuk  = SuratMasuk::orderBy('kode_surat')->get(['id', 'kode_surat', 'perihal']);
        $keluar = SuratKeluar::orderBy('kode_surat')->get(['id', 'kode_surat', 'perihal']);

        return response()->json([
            'surat_masuk'  => $masuk,
            'surat_keluar' => $keluar,
        ]);
    }
}
