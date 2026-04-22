<?php

namespace App\Livewire\Admin;

use App\Models\Permohonan;
use App\Models\Setting;
use App\Mail\NotifikasiStatus;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
#[Title('Permohonan Masuk - Admin UPBJ')]
class PermohonanMasuk extends Component
{
    use WithPagination;

    public $search = '';
    public $monthFilter = '';
    public $yearFilter = '';
    public $perPage = 10;

    public $isModalOpen = false;
    public $selectedData = null;
    public $isPdfModalOpen = false;
    public $previewPdfUrl = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingMonthFilter() { $this->resetPage(); }
    public function updatingYearFilter() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

    public function mount()
    {
        $this->yearFilter = date('Y');
    }

    public function render()
    {
        $query = Permohonan::with(['user', 'unit'])->withCount('items')->where('status', 'Baru');
        
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhereHas('unit', function($subQ) {
                      $subQ->where('nama_unit', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if (!empty($this->monthFilter)) {
            $query->whereMonth('tanggal', $this->monthFilter);
        }

        if (!empty($this->yearFilter)) {
            $query->whereYear('tanggal', $this->yearFilter);
        }

        $permohonanMasuk = $query->latest()->paginate($this->perPage);

        $availableYears = Permohonan::selectRaw('YEAR(tanggal) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        if($availableYears->isEmpty()){
            $availableYears = collect([date('Y')]);
        }

        return view('livewire.admin.permohonan-masuk', compact('permohonanMasuk', 'availableYears'));
    }

    // --- MANAJEMEN MODAL ---
    public function showDetail($id)
    {
        // Pastikan memanggil relasi dokumenLampirans yang baru
        $this->selectedData = Permohonan::with(['user', 'unit', 'items', 'dokumenLampirans'])
            ->where('id', $id)
            ->where('status', 'Baru')
            ->firstOrFail();
            
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedData = null;
    }

    public function openPdfPreview($filePath)
    {
        $this->previewPdfUrl = Storage::url($filePath);
        $this->isPdfModalOpen = true;
    }

    public function closePdfPreview()
    {
        $this->isPdfModalOpen = false;
        $this->previewPdfUrl = null;
    }

    // --- AKSI ADMIN ---
    public function prosesPermohonan($id)
    {
        $permohonan = Permohonan::with('user')->find($id);
        
        if ($permohonan && $permohonan->status == 'Baru') {
            $permohonan->update(['status' => 'Proses']);
            
            $this->kirimNotifikasiKeUser($permohonan, 'Proses');
            
            $this->closeModal();
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Permohonan berhasil diterima dan masuk ke tahap Proses Pekerjaan.'
            ]);
        }
    }

    public function tolakPermohonan($id)
    {
        $permohonan = Permohonan::with('user')->find($id);
        
        if ($permohonan && $permohonan->status == 'Baru') {
            $permohonan->update(['status' => 'Ditolak']);
            
            $this->kirimNotifikasiKeUser($permohonan, 'Ditolak');
            
            $this->closeModal();
            $this->dispatch('toast', [
                'type' => 'error', 
                'message' => 'Permohonan telah ditolak.'
            ]);
        }
    }

    // --- FUNGSI NOTIFIKASI DINAMIS ---
   // --- FUNGSI NOTIFIKASI DINAMIS ---
    protected function kirimNotifikasiKeUser($permohonan, $status)
    {
        $emailUser = $permohonan->user->email ?? null;

        // 1. Logika Notifikasi Email (Synchronous)
        if ($emailUser && Setting::get('is_email_active', true)) {
            try {
                $permohonan->load(['dokumenLampirans', 'items', 'unit', 'user']);
                Mail::to($emailUser)->send(new NotifikasiStatus($permohonan, $status));
            } catch (\Exception $e) {
                // Silent Error Log agar sistem tidak berhenti jika koneksi email gagal
            }
        }

        // 2. Logika Notifikasi WhatsApp (Gateway Fonnte)
        if (Setting::get('is_wa_active', false)) {
            $noHpUser = $permohonan->user->no_hp ?? null;
            $apiToken = Setting::get('wa_api_token');

            if ($noHpUser && $apiToken) {
                // Siapkan variabel untuk template
                $statusIcon = $status == 'Proses' ? '✅' : '❌';
                $statusText = $status == 'Proses' ? '*DITERIMA & DIPROSES*' : '*DITOLAK*';
                $tiket = str_pad($permohonan->id, 5, '0', STR_PAD_LEFT);
                $namaUnit = $permohonan->unit->nama_unit ?? 'Terkait';

                // Template Pesan WA
                $pesan = "Halo *Unit {$namaUnit}*,\n\n";
                $pesan .= "Permohonan pengadaan barang/jasa Anda telah ditinjau oleh Tim UPBJ POLMED.\n\n";
                $pesan .= "🏷️ *No. Tiket:* #PRM-{$tiket}\n";
                $pesan .= "📌 *Judul:* {$permohonan->judul}\n";
                $pesan .= "📊 *Status:* {$statusIcon} {$statusText}\n\n";
                
                if ($status == 'Proses') {
                    $pesan .= "Tim kami akan segera menindaklanjuti permohonan ini. Silakan pantau dashboard untuk update selanjutnya.\n\n";
                } else {
                    $pesan .= "Mohon maaf, permohonan ini belum dapat kami proses saat ini. Silakan hubungi Admin untuk informasi lebih lanjut.\n\n";
                }
                
                $pesan .= "Terima kasih,\n*Sistem UPBJ POLMED*";

                // Tembak API Fonnte menggunakan Laravel HTTP Client
                try {
                    \Illuminate\Support\Facades\Http::withHeaders([
                        'Authorization' => $apiToken,
                    ])->post('https://api.fonnte.com/send', [
                        'target' => $noHpUser,
                        'message' => $pesan,
                        'countryCode' => '62', // Otomatis mengubah awalan 08 menjadi 628
                    ]);
                } catch (\Exception $e) {
                    // Silent fail jika WA gagal kirim agar aplikasi tidak crash
                }
            }
        }
    }
}