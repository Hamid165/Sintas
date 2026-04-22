<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KunjunganTamu;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index()
    {
        return response()->json(KunjunganTamu::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_kegiatan'     => 'required|string|max:255',
            'nama_tamu'          => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'required|date',
            'deskripsi_laporan'  => 'required|string',
            'foto_kegiatan'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'nomor_surat_ref'    => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto_kegiatan')) {
            $validated['foto_kegiatan'] = $request->file('foto_kegiatan')->store('kunjungan', 'public');
        }

        $kunjungan = KunjunganTamu::create($validated);
        return response()->json($kunjungan, 201);
    }

    public function show($id)
    {
        $kunjungan = KunjunganTamu::findOrFail($id);
        return response()->json($kunjungan);
    }

    public function update(Request $request, $id)
    {
        $kunjungan = KunjunganTamu::findOrFail($id);

        $data = $request->validate([
            'judul_kegiatan'     => 'sometimes|required|string|max:255',
            'nama_tamu'          => 'sometimes|required|string|max:255',
            'tanggal_pelaksanaan' => 'sometimes|required|date',
            'deskripsi_laporan'  => 'sometimes|required|string',
            'foto_kegiatan'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'nomor_surat_ref'    => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('foto_kegiatan')) {
            // Hapus gambar lama jika ada
            if ($kunjungan->foto_kegiatan && \Storage::disk('public')->exists($kunjungan->foto_kegiatan)) {
                \Storage::disk('public')->delete($kunjungan->foto_kegiatan);
            }
            $data['foto_kegiatan'] = $request->file('foto_kegiatan')->store('kunjungan', 'public');
        }

        $kunjungan->update($data);
        return response()->json($kunjungan);
    }

    public function destroy($id)
    {
        $kunjungan = KunjunganTamu::findOrFail($id);
        if ($kunjungan->foto_kegiatan && \Storage::disk('public')->exists($kunjungan->foto_kegiatan)) {
            \Storage::disk('public')->delete($kunjungan->foto_kegiatan);
        }
        $kunjungan->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
