<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function index()
    {
        return response()->json(Inventaris::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'stok'        => 'required|integer|min:0',
            'kondisi'     => 'required|string|max:100',
            'kategori'    => 'required|string|max:100',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('inventaris', 'public');
        }

        $inventaris = Inventaris::create($validated);
        return response()->json($inventaris, 201);
    }

    public function show($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        return response()->json($inventaris);
    }

    public function update(Request $request, $id)
    {
        $inventaris = Inventaris::findOrFail($id);

        $data = $request->validate([
            'nama_barang' => 'sometimes|required|string|max:255',
            'stok'        => 'sometimes|required|integer|min:0',
            'kondisi'     => 'sometimes|required|string|max:100',
            'kategori'    => 'sometimes|required|string|max:100',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($inventaris->gambar) {
                \Storage::disk('public')->delete($inventaris->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('inventaris', 'public');
        }

        $inventaris->update($data);
        return response()->json($inventaris);
    }

    public function destroy($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        if ($inventaris->gambar) {
            \Storage::disk('public')->delete($inventaris->gambar);
        }
        $inventaris->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
