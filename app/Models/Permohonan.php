<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    use HasFactory;

    protected $table = 'tb_permohonans';

    protected $fillable = [
        'user_id', 
        'unit_id', 
        'judul', 
        'tanggal', 
        'file_pdf', 
        'status'
    ];

    public function items()
    {
        return $this->hasMany(ItemPermohonan::class, 'permohonan_id');
    }

    // Relasi BelongsTo: Permohonan ini milik User siapa
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi BelongsTo: Permohonan ini dari Unit mana
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}