<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom no_hp setelah kolom email
            // Dibuat nullable() agar user lama yang belum punya no_hp tidak error
            $table->string('no_hp', 20)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom no_hp jika kita melakukan rollback
            $table->dropColumn('no_hp');
        });
    }
};