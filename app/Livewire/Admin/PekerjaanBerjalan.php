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
        $this->yearFilter = date('Y');
    }

    public function render()
    {
        $query = Permohonan::with(['user', 'unit'])->withCount('items')->where('status', 'Proses');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhereHas('unit', function($subQ) {
                      $subQ->where('nama_unit', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if (!empty($this->monthFilter)) {
            $query->whereMonth('updated_at', $this->monthFilter);
        }

        if (!empty($this->yearFilter)) {
            $query->whereYear('updated_at', $this->yearFilter);
        }

        $pekerjaan = $query->latest('updated_at')->paginate($this->perPage);

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
        // PASTIKAN MEMANGGIL RELASI dokumenLampirans
        $this->selectedData = Permohonan::with(['user', 'unit', 'items', 'dokumenLampirans'])
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
        $permohonan = Permohonan::with('user')->find($id);
        
        if ($permohonan && $permohonan->status == 'Proses') {
            $permohonan->update(['status' => 'Selesai']);
            
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

        if ($emailUser) {
            if (Setting::get('is_email_active', true)) {
                try {
                    // Load relasi agar email bisa membaca data item dan melampirkan banyak PDF
                    $permohonan->load(['dokumenLampirans', 'items', 'unit', 'user']);
                    Mail::to($emailUser)->send(new NotifikasiStatus($permohonan, 'Selesai'));
                } catch (\Exception $e) {
                    // Silent fail
                }
            }
        }

        if (Setting::get('is_wa_active', false)) {
            $noHpUser = $permohonan->user->no_hp ?? null;
            $apiToken = Setting::get('wa_api_token');
            
            if ($noHpUser && $apiToken) {
                // $pesan = "Halo Unit {$permohonan->unit->nama_unit}, Pekerjaan pengadaan untuk '{$permohonan->judul}' telah SELESAI direalisasikan.";
                // Nanti logika cURL ke API Fonnte diletakkan di sini
            }
        }
    }
}