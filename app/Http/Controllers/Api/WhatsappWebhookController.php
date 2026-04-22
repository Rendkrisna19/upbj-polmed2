<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsappWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Ambil data dari Fonnte
        $sender = $request->input('sender');    // Nomor pengirim
        $message = $request->input('message');  // Isi pesan
        
        // Contoh Logika: Simpan ke log untuk tracking
        Log::info("WA Masuk dari $sender: $message");

        // Kamu bisa buat logika auto-reply di sini jika mau
        
        return response()->json(['status' => 'success']);
    }
}