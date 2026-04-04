<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_permohonans', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (Siapa yang buat)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            
            // Relasi ke tabel tb_unit yang sudah kita buat sebelumnya
            $table->foreignId('unit_id')->constrained('tb_unit')->onDelete('cascade'); 
            
            $table->string('judul');
            $table->date('tanggal');
            $table->string('file_pdf')->nullable();
            $table->enum('status', ['Baru', 'Proses', 'Selesai', 'Ditolak'])->default('Baru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_permohonans');
    }
};