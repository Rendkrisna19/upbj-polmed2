<?php

namespace App\Mail;

use App\Models\Permohonan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifikasiStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $permohonan;
    public $statusPesanan;

    public function __construct(Permohonan $permohonan, $statusPesanan)
    {
        $this->permohonan = $permohonan;
        $this->statusPesanan = $statusPesanan;
    }

    public function build()
    {
        // Logika 3 Status
        if ($this->statusPesanan == 'Proses') {
            $subjectText = '✅ Permohonan Diterima & Sedang Diproses';
        } elseif ($this->statusPesanan == 'Selesai') {
            $subjectText = '🎉 Pekerjaan Selesai Direalisasikan';
        } else {
            $subjectText = '❌ Permohonan Ditolak';
        }

        return $this->subject('Update Status: ' . $subjectText . ' - UPBJ POLMED')
                    ->view('emails.notifikasi_status');
    }
}