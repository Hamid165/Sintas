<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('karyawan')->after('email')->comment('admin, sekretariat, bendahara');
            $table->string('jabatan')->nullable()->after('role'); // Misal: Kepala Panti, Pengasuh
            $table->string('no_hp')->nullable()->after('jabatan');
            
            // Tambahkan ini untuk Struktur Organisasi
            $table->unsignedBigInteger('parent_id')->nullable()->after('no_hp');
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('set null');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'jabatan', 'no_hp']);
        });
    }
};