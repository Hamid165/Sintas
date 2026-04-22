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
        // Surat Masuk
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat')->unique()->comment('Surat Masuk Format: SRT-IN-YYYY-NNN');
            $table->string('perihal')->comment('Perihal/Subject of the letter');
            $table->string('pengirim')->comment('Sender/Organization');
            $table->date('tanggal_surat')->comment('Date of the letter');
            $table->date('tanggal_diterima')->comment('Date received');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Surat Keluar
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat')->unique()->comment('Surat Keluar Format: SRT-OUT-YYYY-NNN');
            $table->string('perihal')->comment('Perihal/Subject of the letter');
            $table->string('tujuan')->comment('Recipient/Organization');
            $table->date('tanggal_surat')->comment('Date of the letter');
            $table->date('tanggal_dikirim')->comment('Date sent');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
        Schema::dropIfExists('surat_masuks');
    }
};
