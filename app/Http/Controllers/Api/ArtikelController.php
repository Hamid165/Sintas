<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index()
    {
        return response()->json(Artikel::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'            => 'required|string|max:255',
            'deskripsi_konten' => 'required|string',
            'gambar_konten'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        if ($request->hasFile('gambar_konten')) {
            $validated['gambar_konten'] = $request->file('gambar_konten')->store('artikel', 'public');
        }

        $artikel = Artikel::create($validated);
        return response()->json($artikel, 201);
    }

    public function show($id)
    {
        $artikel = Artikel::findOrFail($id);
        return response()->json($artikel);
    }

    public function update(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);

        $data = $request->validate([
            'judul'            => 'sometimes|required|string|max:255',
            'deskripsi_konten' => 'sometimes|required|string',
            'gambar_konten'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        if ($request->hasFile('gambar_konten')) {
            // Hapus gambar lama jika ada
            if ($artikel->gambar_konten && \Storage::disk('public')->exists($artikel->gambar_konten)) {
                \Storage::disk('public')->delete($artikel->gambar_konten);
            }
            $data['gambar_konten'] = $request->file('gambar_konten')->store('artikel', 'public');
        }

        $artikel->update($data);
        return response()->json($artikel);
    }

    public function destroy($id)
    {
        $artikel = Artikel::findOrFail($id);
        if ($artikel->gambar_konten && \Storage::disk('public')->exists($artikel->gambar_konten)) {
            \Storage::disk('public')->delete($artikel->gambar_konten);
        }
        $artikel->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
