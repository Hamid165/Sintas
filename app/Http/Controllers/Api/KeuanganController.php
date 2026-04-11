<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index()
    {
        return response()->json(Keuangan::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jumlah_nominal' => 'required|numeric',
            'jenis_transaksi' => 'required|string',
            'kategori' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $keuangan = Keuangan::create($validated);
        return response()->json($keuangan, 201);
    }

    public function show($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        return response()->json($keuangan);
    }

    public function update(Request $request, $id)
    {
        $keuangan = Keuangan::findOrFail($id);
        $keuangan->update($request->all());
        return response()->json($keuangan);
    }

    public function destroy($id)
    {
        $keuangan = Keuangan::findOrFail($id);
        $keuangan->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
