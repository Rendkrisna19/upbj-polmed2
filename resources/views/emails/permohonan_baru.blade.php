<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Permohonan Baru UPBJ</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f4f7ff; padding: 20px;">
    
    <div style="max-width: 650px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e1e8f0;">
        
        <div style="background: #7c3aed; padding: 30px; text-align: center;">
            <h1 style="color: #fff; margin: 0; font-size: 24px; letter-spacing: 1px;">UPBJ <span style="font-weight: 300;">POLMED</span></h1>
            <p style="color: #ddd6fe; margin: 5px 0 0 0; font-size: 14px;">Sistem Informasi Pengadaan Barang & Jasa</p>
        </div>

        <div style="padding: 30px;">
            <h2 style="color: #1f2937; margin-top: 0; font-size: 20px;">Halo Admin UPBJ,</h2>
            <p style="color: #4b5563;">Terdapat pengajuan permohonan baru dari unit kerja yang memerlukan peninjauan Anda segera.</p>
            
            <div style="background: #f9fafb; border-radius: 8px; padding: 20px; margin-bottom: 25px; border: 1px solid #edf2f7;">
                <h3 style="margin-top: 0; font-size: 16px; color: #7c3aed; border-bottom: 2px solid #ddd6fe; display: inline-block; padding-bottom: 3px;">Ringkasan Pengajuan</h3>
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <tr>
                        <td style="padding: 5px 0; font-weight: bold; color: #6b7280; width: 35%;">Unit Pengirim</td>
                        <td style="padding: 5px 0; color: #111827;">: {{ $permohonan->unit->nama_unit ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; font-weight: bold; color: #6b7280;">Judul Kegiatan</td>
                        <td style="padding: 5px 0; color: #111827;">: {{ $permohonan->judul }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; font-weight: bold; color: #6b7280;">Tanggal Masuk</td>
                        <td style="padding: 5px 0; color: #111827;">: {{ \Carbon\Carbon::parse($permohonan->created_at)->format('d M Y H:i') }} WIB</td>
                    </tr>
                </table>
            </div>

            <h3 style="font-size: 16px; color: #1f2937; margin-bottom: 10px;">📦 Rincian Barang/Jasa:</h3>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 14px;">
                <thead>
                    <tr style="background: #f3f4f6;">
                        <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: left;">No</th>
                        <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: left;">Nama Item</th>
                        <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: center;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permohonan->items as $index => $item)
                    <tr>
                        <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $index + 1 }}</td>
                        <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $item->nama_item }}</td>
                        <td style="padding: 10px; border: 1px solid #e5e7eb; text-align: center;">{{ $item->jumlah }} Unit</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <h3 style="font-size: 16px; color: #1f2937; margin-bottom: 10px;">📎 Dokumen Lampiran:</h3>
            <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px;">
                @forelse($permohonan->dokumenLampirans as $doc)
                    <div style="margin-bottom: 8px; font-size: 14px; display: flex; align-items: center;">
                        <span style="color: #ef4444; margin-right: 8px;">PDF</span>
                        <span style="color: #374151;">{{ $doc->nama_dokumen ?: basename($doc->file_path) }}</span>
                    </div>
                @empty
                    <p style="color: #9ca3af; font-size: 14px; font-style: italic; margin: 0;">Tidak ada dokumen dilampirkan.</p>
                @endforelse
                @if($permohonan->dokumenLampirans->count() > 0)
                    <p style="color: #6b7280; font-size: 12px; margin-top: 10px;">* Seluruh file di atas telah dilampirkan dalam email ini.</p>
                @endif
            </div>

            <div style="margin-top: 40px; text-align: center;">
                <a href="{{ route('login') }}" style="background: #7c3aed; color: white; padding: 14px 30px; text-decoration: none; border-radius: 10px; font-weight: bold; display: inline-block; box-shadow: 0 4px 6px rgba(124, 58, 237, 0.2);">
                    Lihat & Proses di Dashboard
                </a>
            </div>

        </div>

        <div style="background: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #edf2f7;">
            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                Email ini dihasilkan secara otomatis oleh <strong>Sistem UPBJ POLMED</strong>.<br>
                Mohon tidak membalas email ini secara langsung.
            </p>
        </div>
    </div>

</body>
</html>