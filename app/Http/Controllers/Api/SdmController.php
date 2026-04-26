<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SdmController extends Controller
{
    public function index()
    {
        $users = User::orderByRaw("CASE WHEN role = 'admin' THEN 1 ELSE 2 END")
                     ->orderBy('id', 'desc')
                     ->get();
                     
        // Attach spatie roles explicitly if needed, or map it
        $users->map(function ($u) {
            $u->spatie_roles = class_uses($u, \Spatie\Permission\Traits\HasRoles::class) ? $u->getRoleNames() : [$u->role];
            return $u;
        });

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'role'      => 'required',
            'jabatan'   => 'required',
        ]);

        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'plain_password' => $request->password,
            'role'           => $request->role,
            'jabatan'        => $request->jabatan,
        ]);

        if (class_uses($user, \Spatie\Permission\Traits\HasRoles::class)) {
            $user->assignRole($request->role);
        }

        return response()->json(['message' => 'Anggota berhasil ditambahkan!', 'data' => $user], 201);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        User::where('parent_id', $id)->update(['parent_id' => null]);
        $user->delete();

        return response()->json(['message' => 'Anggota berhasil dihapus.']);
    }
    
    public function roles()
    {
        return response()->json(Role::all());
    }
}
