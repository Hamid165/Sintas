<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunjunganTamu extends Model
{
    protected $table = 'artikels';

    protected $fillable = [
        'judul_kegiatan',
        'nama_tamu',
        'tanggal_pelaksanaan',
        'foto_kegiatan',
        'deskripsi_laporan',
        'nomor_surat_ref',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
    ];

    /**
     * Get publicly accessible URL for foto kegiatan
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto_kegiatan) return null;
        return asset('storage/' . $this->foto_kegiatan);
    }
}
