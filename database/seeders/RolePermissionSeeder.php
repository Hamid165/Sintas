<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Permissions (Menu & Aksi)
        $permissions = [
            // Menu Anak
            'view_anak', 'create_anak', 'edit_anak', 'delete_anak',
            // Menu Keuangan
            'view_keuangan', 'create_keuangan', 'edit_keuangan', 'delete_keuangan',
            // Menu Kunjungan
            'view_kunjungan', 'create_kunjungan', 'edit_kunjungan', 'delete_kunjungan',
            // Menu Inventaris
            'view_inventori', 'create_inventori', 'edit_inventori', 'delete_inventori',
            // Menu Surat
            'view_surat', 'create_surat', 'edit_surat', 'delete_surat',
            // Menu Audit Keuangan
            'view_audit', 'create_audit', 'edit_audit', 'delete_audit',
            // Manajemen SDM
            'view_sdm', 'create_sdm', 'edit_sdm', 'delete_sdm',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Buat Roles & Berikan Default Permission
        $roleAdmin = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        $roleSekretariat = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'sekretariat']);
        $roleSekretariat->givePermissionTo([
            'view_surat', 'create_surat', 'edit_surat', 'delete_surat',
            'view_anak', 'view_kunjungan'
        ]);

        $roleBendahara = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'bendahara']);
        $roleBendahara->givePermissionTo([
            'view_keuangan', 'create_keuangan', 'edit_keuangan', 'delete_keuangan',
            'view_audit', 'create_audit', 'edit_audit', 'delete_audit',
        ]);

        $roleKaryawan = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'karyawan']);
        $roleKaryawan->givePermissionTo([
            'view_inventori', 'create_inventori', 'edit_inventori', 'delete_inventori',
        ]);

        // 3. Migrate Existing Users
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            if ($user->role && !$user->hasRole($user->role)) {
                try {
                    $user->assignRole($user->role);
                } catch (\Exception $e) {
                    // Abaikan jika role string lama tidak ada di tabel Spatie
                }
            }
        }
    }
}
