<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use Illuminate\Http\Request;

class AnakController extends Controller
{
    public function index()
    {
        return response()->json(Anak::latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_tgl_lahir' => 'required|string|max:255',
            'usia' => 'required|integer',
            'jenis_kelamin' => 'required|string',
            'riwayat_kesehatan' => 'nullable|string',
            'info_pendidikan' => 'nullable|string',
        ]);

        $anak = Anak::create($validated);
        broadcast(new \App\Events\AnakUpdated($anak, 'create'));
        return response()->json($anak, 201);
    }

    public function show($id)
    {
        $anak = Anak::findOrFail($id);
        return response()->json($anak);
    }

    public function update(Request $request, $id)
    {
        $anak = Anak::findOrFail($id);
        $anak->update($request->all());
        broadcast(new \App\Events\AnakUpdated($anak, 'update'));
        return response()->json($anak);
    }

    public function destroy($id)
    {
        $anak = Anak::findOrFail($id);
        $anak->delete();
        broadcast(new \App\Events\AnakUpdated($anak, 'delete'));
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
