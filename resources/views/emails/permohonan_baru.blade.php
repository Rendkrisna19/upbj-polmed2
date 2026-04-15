<!DOCTYPE html>
<html>
<head>
    <title>Permohonan Baru UPBJ</title>
</head>
<body style="font-family: sans-serif; line-height: 1.6; color: #333; background-color: #f9f9f9; padding: 20px;">
    
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border: 1px solid #ddd; border-radius: 10px; border-top: 5px solid #7c3aed;">
        <h2 style="color: #7c3aed; margin-top: 0;">Halo Admin UPBJ,</h2>
        <p>Ada permohonan pengadaan baru yang perlu segera diperiksa. Berikut adalah rinciannya:</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px;">
            <tr>
                <td style="padding: 10px 0; font-weight: bold; border-bottom: 1px solid #eee; width: 35%;">Unit Pengirim</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">: {{ $permohonan->unit->nama_unit ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; font-weight: bold; border-bottom: 1px solid #eee;">Judul Permohonan</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">: {{ $permohonan->judul }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; font-weight: bold; border-bottom: 1px solid #eee;">Waktu Masuk</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">: {{ \Carbon\Carbon::parse($permohonan->created_at)->format('d M Y H:i') }} WIB</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; font-weight: bold; border-bottom: 1px solid #eee;">Status Lampiran</td>
                <td style="padding: 10px 0; border-bottom: 1px solid #eee;">
                    : @if($permohonan->file_pdf) 
                        <span style="color: #059669; font-weight: bold;">Terlampir pada email ini (PDF)</span> 
                      @else 
                        <span style="color: #dc2626;">Tidak ada dokumen dilampirkan</span> 
                      @endif
                </td>
            </tr>
        </table>

        <p style="margin-top: 20px;">
            @if($permohonan->file_pdf)
                Silakan unduh dan periksa dokumen pendukung (PDF) yang terlampir pada email ini. 
            @endif
            Untuk memproses atau menolak permohonan ini, silakan masuk ke sistem aplikasi UPBJ POLMED.
        </p>
        
        <div style="margin-top: 35px; text-align: center;">
            <a href="{{ route('login') }}" style="background: #7c3aed; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block;">Buka Dashboard Admin</a>
        </div>

        <p style="margin-top: 40px; color: #6b7280; font-size: 12px; border-top: 1px solid #eee; padding-top: 15px; text-align: center;">
            Email ini dikirim secara otomatis oleh Sistem UPBJ POLMED. Mohon jangan membalas email ini.
        </p>
    </div>

</body>
</html>