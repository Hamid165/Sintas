<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['judul', 'deskripsi_konten', 'gambar_konten']);
        });

        Schema::table('artikels', function (Blueprint $table) {
            // Add new columns for kunjungan tamu
            $table->string('judul_kegiatan');
            $table->string('nama_tamu');
            $table->date('tanggal_pelaksanaan');
            $table->string('foto_kegiatan')->nullable();
            $table->text('deskripsi_laporan');
            $table->string('nomor_surat_ref')->nullable(); // reference to surat kode
        });
    }

    public function down(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            $table->dropColumn(['judul_kegiatan', 'nama_tamu', 'tanggal_pelaksanaan', 'foto_kegiatan', 'deskripsi_laporan', 'nomor_surat_ref']);
        });

        Schema::table('artikels', function (Blueprint $table) {
            $table->string('judul');
            $table->text('deskripsi_konten');
            $table->string('gambar_konten')->nullable();
        });
    }
};
