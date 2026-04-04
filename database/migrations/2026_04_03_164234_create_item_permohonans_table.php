<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_item_permohonans', function (Blueprint $table) {
            $table->id();
            // Relasi kembali ke tabel permohonan (Satu permohonan punya banyak item)
            $table->foreignId('permohonan_id')->constrained('tb_permohonans')->onDelete('cascade');
            
            $table->string('nama_item'); // Di kolom inilah query DISTINCT akan mencari history barang
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_item_permohonans');
    }
};