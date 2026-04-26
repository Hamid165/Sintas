<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        // Kelompokkan permission
        $permissions = Permission::all()->groupBy(function($item) {
            $parts = explode('_', $item->name);
            return isset($parts[1]) ? ucfirst($parts[1]) : 'Other';
        });

        return response()->json([
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'admin') {
            return response()->json(['message' => 'Role admin tidak dapat diubah'], 403);
        }

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return response()->json(['message' => 'Hak akses untuk role ' . $role->name . ' berhasil diperbarui!']);
    }
}
