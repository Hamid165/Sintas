<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        // Kelompokkan permission agar mudah dirender di view
        $permissions = Permission::all()->groupBy(function($item) {
            $parts = explode('_', $item->name);
            return isset($parts[1]) ? ucfirst($parts[1]) : 'Other';
        });

        return view('admin.sdm.role', compact('roles', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'admin') {
            return redirect()->back()->with('toast', 'Role admin tidak dapat diubah')->with('toast_type', 'error');
        }

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return redirect()->back()->with('toast', 'Hak akses untuk role ' . $role->name . ' berhasil diperbarui!')->with('toast_type', 'success');
    }
}
