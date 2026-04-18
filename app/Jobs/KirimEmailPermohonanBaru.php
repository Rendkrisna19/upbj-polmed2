<?php

namespace App\Jobs;

use App\Models\Permohonan;
use App\Mail\NotifikasiPermohonanBaru;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class KirimEmailPermohonanBaru implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $permohonanId;
    public $adminEmails;

    public function __construct($permohonanId, $adminEmails)
    {
        $this->permohonanId = $permohonanId;
        $this->adminEmails = $adminEmails;
    }

    public function handle(): void
    {
        try {
            // Kita Query ulang datanya DI DALAM Job (Sangat Penting!)
            // Agar semua relasinya (user, unit, items, dokumen) ikut terbaca oleh Mailable
            $permohonanLengkap = Permohonan::with(['user', 'unit', 'items', 'dokumenLampirans'])
                                            ->find($this->permohonanId);
            
            if ($permohonanLengkap) {
                Mail::to($this->adminEmails)->send(new NotifikasiPermohonanBaru($permohonanLengkap));
            }
        } catch (\Exception $e) {
            // Log error jika gagal kirim di background
            \Log::error('Gagal kirim email notifikasi permohonan baru: ' . $e->getMessage());
        }
    }
}