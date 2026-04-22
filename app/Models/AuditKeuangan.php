<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditKeuangan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function keuangan()
    {
        return $this->belongsTo(Keuangan::class);
    }

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'kode_surat', 'kode_surat');
    }

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'kode_surat', 'kode_surat');
    }
}
