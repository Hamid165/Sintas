<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profil;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        // Biasanya profil yayasan/panti hanya ada 1 record
        $profil = Profil::first();
        return response()->json($profil);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'password' => 'nullable|string',
            'foto_profil' => 'nullable|string',
        ]);

        $profil = Profil::first();
        if ($profil) {
            $profil->update($validated);
        } else {
            $profil = Profil::create($validated);
        }

        return response()->json($profil, 201);
    }

    public function update(Request $request, $id)
    {
        $profil = Profil::findOrFail($id);
        $profil->update($request->all());
        return response()->json($profil);
    }
}
