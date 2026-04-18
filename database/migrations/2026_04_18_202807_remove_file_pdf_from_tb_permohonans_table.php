<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_permohonans', function (Blueprint $table) {
            // Menghapus kolom file_pdf
            $table->dropColumn('file_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('tb_permohonans', function (Blueprint $table) {
            // Mengembalikan kolom jika kita melakukan rollback
            $table->string('file_pdf')->nullable();
        });
    }
};