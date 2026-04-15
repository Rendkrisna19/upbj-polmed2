<?php

namespace App\Mail;

use App\Models\Permohonan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage; // Wajib ditambahkan untuk mengambil file

class NotifikasiPermohonanBaru extends Mailable
{
    use Queueable, SerializesModels;

    public $permohonan;

    public function __construct(Permohonan $permohonan)
    {
        $this->permohonan = $permohonan;
    }

    public function build()
    {
        // 1. Siapkan subject dan view email
        $mail = $this->subject('Notifikasi: Permohonan Baru Masuk - UPBJ POLMED')
                     ->view('emails.permohonan_baru');

        // 2. Cek apakah permohonan ini punya file_pdf dan file-nya benar-benar ada di folder storage/public
        if ($this->permohonan->file_pdf && Storage::disk('public')->exists($this->permohonan->file_pdf)) {
            
            // 3. Lampirkan file ke dalam email
            $mail->attach(Storage::disk('public')->path($this->permohonan->file_pdf), [
                'as' => 'Dokumen_Permohonan_REQ-' . str_pad($this->permohonan->id, 4, '0', STR_PAD_LEFT) . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}