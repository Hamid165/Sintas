<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $dates = ['tanggal_surat', 'tanggal_diterima'];

    public function auditKeuangan()
    {
        return $this->hasMany(AuditKeuangan::class, 'kode_surat', 'kode_surat');
    }
}
