<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Kepala Panti (Atasan Tertinggi - parent_id NULL)
        $kepalaPanti = User::create([
            'name'     => 'Atmin Utama',
            'email'    => 'admin@CareHub.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
            'jabatan'  => 'Kepala Panti',
            'no_hp'    => '081234567890',
            'parent_id' => null,
        ]);

        // 2. Buat Bendahara (Melapor ke Kepala Panti)
        $bendahara = User::create([
            'name'     => 'Bendahara Abdul',
            'email'    => 'bendahara@CareHub.com',
            'password' => Hash::make('password123'),
            'role'     => 'bendahara',
            'jabatan'  => 'Bendahara Yayasan',
            'no_hp'    => '081299998888',
            'parent_id' => $kepalaPanti->id, // Relasi ke Kepala Panti
        ]);

        // 3. Buat Sekretaris (Melapor ke Kepala Panti)
        $sekretaris = User::create([
            'name'     => 'Sekretaris Siti',
            'email'    => 'sekretariat@CareHub.com',
            'password' => Hash::make('password123'),
            'role'     => 'sekretariat',
            'jabatan'  => 'Sekretaris Utama',
            'no_hp'    => '081277776666',
            'parent_id' => $kepalaPanti->id, // Relasi ke Kepala Panti
        ]);

        // 4. Buat Karyawan/Pengasuh (Melapor ke Sekretaris atau Kepala)
        User::create([
            'name'     => 'Pengasuh Ahmad',
            'email'    => 'karyawan1@CareHub.com',
            'password' => Hash::make('password123'),
            'role'     => 'karyawan',
            'jabatan'  => 'Pengasuh Anak',
            'no_hp'    => '081255554444',
            'parent_id' => $sekretaris->id, // Melapor ke Sekretaris
        ]);

        // Tambahkan dummy Profil Panti
        \App\Models\Profil::create([
            'nama' => 'Panti Asuhan CareHub',
            'foto_profil' => null,
        ]);
    }
}