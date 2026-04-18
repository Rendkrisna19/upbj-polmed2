<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenLampiran extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel yang benar
    protected $table = 'tb_dokumen_lampirans';

    protected $fillable = [
        'permohonan_id',
        'nama_dokumen',
        'file_path',
    ];

    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }
}