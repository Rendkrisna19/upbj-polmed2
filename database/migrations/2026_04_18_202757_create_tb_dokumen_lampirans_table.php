<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_dokumen_lampirans', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel tb_permohonans (Cascade: jika permohonan dihapus, file ikut terhapus)
            $table->foreignId('permohonan_id')->constrained('tb_permohonans')->onDelete('cascade');
            
            // Nama dokumen (opsional sesuai permintaanmu)
            $table->string('nama_dokumen')->nullable(); 
            
            // Lokasi penyimpanan file di server
            $table->string('file_path'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_dokumen_lampirans');
    }
};