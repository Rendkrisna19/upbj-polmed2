<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPermohonan extends Model
{
    use HasFactory;

    protected $table = 'tb_item_permohonans';

    protected $fillable = [
        'permohonan_id', 
        'nama_item', 
        'jumlah'
    ];

    // Relasi Inverse: Item ini milik Permohonan yang mana
    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }
}