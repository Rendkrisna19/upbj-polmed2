<!DOCTYPE html>
<html>
<head>
    <title>Update Status Permohonan</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; padding: 20px;">
    
    @php
        if ($statusPesanan == 'Proses') {
            $borderColor = '#059669'; // Emerald (Hijau)
            $statusText  = 'DITERIMA & SEDANG DIPROSES';
            $pesanBawah  = 'Tim kami akan segera menindaklanjuti permohonan ini sesuai dengan prosedur pengadaan yang berlaku. Anda dapat memantau status perkembangan lebih lanjut melalui dashboard aplikasi.';
        } elseif ($statusPesanan == 'Selesai') {
            $borderColor = '#2563eb'; // Royal Blue (Biru)
            $statusText  = 'SELESAI DIREALISASIKAN';
            $pesanBawah  = 'Pekerjaan pengadaan untuk permohonan ini telah selesai dikerjakan/direalisasikan. Terima kasih atas kerja samanya dengan tim UPBJ POLMED.';
        } else {
            $borderColor = '#dc2626'; // Red (Merah)
            $statusText  = 'DITOLAK';
            $pesanBawah  = 'Mohon maaf, permohonan ini belum dapat kami proses saat ini. Silakan hubungi Admin UPBJ untuk informasi lebih lanjut atau perbaikan dokumen.';
        }
    @endphp

    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border: 1px solid #ddd; border-radius: 10px; border-top: 5px solid {{ $borderColor }};">
        
        <h2 style="color: {{ $borderColor }}; margin-top: 0;">Halo, Unit {{ $permohonan->user->unit ?? 'Terkait' }}</h2>
        <p>Permohonan pengadaan Anda telah ditinjau dan diperbarui oleh Tim UPBJ POLMED dengan hasil sebagai berikut:</p>
        
        <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; margin: 25px 0; text-align: center;">
            <p style="margin: 0; font-size: 14px; color: #6b7280;">Status Saat Ini:</p>
            <p style="margin: 5px 0 0 0; font-size: 20px; font-weight: bold; color: {{ $borderColor }};">
                {{ $statusText }}
            </p>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="padding: 10px 0; font-weight: bold; border-bottom: 1px solid #eee; width: 35%;">ID Referensi</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">: #REQ-{{ str_pad($permohonan->id, 4, '0', STR_PAD_LEFT) }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; font-weight: bold; border-bottom: 1px solid #eee;">Judul Permohonan</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">: {{ $permohonan->judul }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; font-weight: bold; border-bottom: 1px solid #eee;">Tanggal Pengajuan</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">: {{ \Carbon\Carbon::parse($permohonan->created_at)->translatedFormat('d F Y') }}</td>
            </tr>
        </table>

        <p style="margin-top: 20px;">
            {{ $pesanBawah }}
        </p>
        
        <div style="margin-top: 35px; text-align: center;">
            <a href="{{ route('login') }}" style="background: {{ $borderColor }}; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Buka Dashboard Aplikasi</a>
        </div>

        <p style="margin-top: 40px; color: #6b7280; font-size: 12px; border-top: 1px solid #eee; padding-top: 15px; text-align: center;">
            Email ini dikirim secara otomatis oleh Sistem UPBJ POLMED. Mohon jangan membalas email ini.
        </p>
    </div>

</body>
</html>