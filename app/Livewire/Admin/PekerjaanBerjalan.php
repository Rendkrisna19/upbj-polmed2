<?php

namespace App\Livewire\Admin;

use App\Models\Permohonan;
use App\Models\Setting; // Memanggil tabel setting dinamis
use App\Mail\NotifikasiStatus; // Memanggil class Mailable
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
#[Title('Pekerjaan Berjalan - Admin UPBJ')]
class PekerjaanBerjalan extends Component
{
    use WithPagination;

    public $search = '';
    public $monthFilter = '';
    public $yearFilter = '';
    public $perPage = 10;
    
    // Properti Modal
    public $isModalOpen = false;
    public $selectedData = null;

    public $isPdfModalOpen = false;
    public $previewPdfUrl = null;

    // Reset pagination ketika filter diubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingMonthFilter() { $this->resetPage(); }
    public function updatingYearFilter() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

    public function mount()
    {
        // Set default filter tahun ke tahun saat ini
        $this->yearFilter = date('Y');
    }

    public function render()
    {
        // OPTIMASI: Gunakan withCount untuk performa tabel yang lebih ringan
        $query = Permohonan::with(['user', 'unit'])->withCount('items')->where('status', 'Proses');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhereHas('unit', function($subQ) {
                      $subQ->where('nama_unit', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter berdasarkan waktu mulai diproses (updated_at)
        if (!empty($this->monthFilter)) {
            $query->whereMonth('updated_at', $this->monthFilter);
        }

        if (!empty($this->yearFilter)) {
            $query->whereYear('updated_at', $this->yearFilter);
        }

        $pekerjaan = $query->latest('updated_at')->paginate($this->perPage);

        // Ambil daftar tahun unik dari data yang sedang diproses
        $availableYears = Permohonan::where('status', 'Proses')
                            ->selectRaw('YEAR(updated_at) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        if($availableYears->isEmpty()){
            $availableYears = collect([date('Y')]);
        }

        return view('livewire.admin.pekerjaan-berjalan', compact('pekerjaan', 'availableYears'));
    }

    public function showDetail($id)
    {
        // Panggil relasi items secara penuh hanya saat modal detail dibuka
        $this->selectedData = Permohonan::with(['user', 'unit', 'items'])
            ->where('id', $id)
            ->where('status', 'Proses')
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

    // Eksekusi: Ubah status dari Proses -> Selesai
    public function selesaikanPekerjaan($id)
    {
        // Pastikan memanggil relasi 'user' agar email dan no_hp bisa diakses
        $permohonan = Permohonan::with('user')->find($id);
        
        if ($permohonan && $permohonan->status == 'Proses') {
            $permohonan->update(['status' => 'Selesai']);
            
            // Panggil fungsi kirim notifikasi
            $this->kirimNotifikasiSelesai($permohonan);
            
            $this->closeModal();
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Luar biasa! Pekerjaan telah ditandai Selesai dan dipindah ke Realisasi.'
            ]);
        }
    }

    // --- FUNGSI NOTIFIKASI DINAMIS ---
    protected function kirimNotifikasiSelesai($permohonan)
    {
        $emailUser = $permohonan->user->email ?? null;

        // 1. Logika Pengiriman Email
        if ($emailUser) {
            // Cek apakah fitur email diaktifkan oleh Super Admin
            if (Setting::get('is_email_active', true)) {
                try {
                    // Mengirim email dengan status 'Selesai'
                    Mail::to($emailUser)->send(new NotifikasiStatus($permohonan, 'Selesai'));
                } catch (\Exception $e) {
                    // Silent fail: Abaikan jika error agar tidak menghentikan proses
                }
            }
        }

        // 2. Persiapan Logika Pengiriman WhatsApp (Ga`teway Fonnte)
        /*
        $noHpUser = $permohonan->user->no_hp ?? null;
        
        if ($noHpUser && Setting::get('is_wa_active', true)) {
            $apiToken = Setting::get('wa_api_token');
            $pesan = "Halo Unit {$permohonan->unit->nama_unit}, Pekerjaan pengadaan untuk '{$permohonan->judul}' telah SELESAI direalisasikan.";
            
            // Contoh implementasi cURL ke API Fonnte:
            // $curl = curl_init();
            // curl_setopt_array($curl, array(
            //   CURLOPT_URL => 'https://api.fonnte.com/send',
            //   CURLOPT_RETURNTRANSFER => true,
            //   CURLOPT_POST => true,
            //   CURLOPT_POSTFIELDS => array(
            //     'target' => $noHpUser,
            //     'message' => $pesan,
            //   ),
            //   CURLOPT_HTTPHEADER => array(
            //     "Authorization: $apiToken"
            //   ),
            // ));
            // curl_exec($curl);
            // curl_close($curl);
        }
        */
    }
}