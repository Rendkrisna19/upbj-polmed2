<?php

namespace App\Mail;

use App\Models\Permohonan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NotifikasiPermohonanBaru extends Mailable
{
    use Queueable, SerializesModels;

    public $permohonan;

    public function __construct(Permohonan $permohonan)
    {
        // Pastikan kita me-load relasi agar datanya lengkap saat di view
        $this->permohonan = $permohonan->load(['user', 'unit', 'items', 'dokumenLampirans']);
    }

    public function build()
    {
        $mail = $this->subject('🔔 Notifikasi: Permohonan Baru - ' . $this->permohonan->unit->nama_unit)
                     ->view('emails.permohonan_baru');

        // Logic melampirkan semua dokumen lampiran
        foreach ($this->permohonan->dokumenLampirans as $doc) {
            if (Storage::disk('public')->exists($doc->file_path)) {
                
                // Gunakan nama_dokumen jika ada, jika tidak gunakan nama default
                $fileName = $doc->nama_dokumen 
                            ? $doc->nama_dokumen . '.pdf' 
                            : 'Lampiran_' . basename($doc->file_path);

                $mail->attach(Storage::disk('public')->path($doc->file_path), [
                    'as' => $fileName,
                    'mime' => 'application/pdf',
                ]);
            }
        }

        return $mail;
    }
}