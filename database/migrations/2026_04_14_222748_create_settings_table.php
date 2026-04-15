<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            
            // 'group' ini yang membedakan mana settingan WA dan mana Email
            $table->string('group')->default('general'); 
            
            // 'label' untuk nama yang tampil di form UI Super Admin
            $table->string('label'); 
            
            // 'key' adalah kode unik panggilannya (cth: is_wa_active)
            $table->string('key')->unique(); 
            
            // 'value' adalah isinya (1=Aktif, 0=Mati, atau text panjang)
            $table->text('value')->nullable(); 
            
            // 'type' untuk menentukan bentuk input form (toggle/boolean, teks, atau password)
            $table->string('type')->default('string'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};