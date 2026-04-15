<?php

namespace App\Livewire\User;

use App\Models\Permohonan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage; 

#[Layout('layouts.app')]
#[Title('Riwayat & Status - UPBJ POLMED')]
class RiwayatPermohonan extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = ''; 
    public $monthFilter = '';
    public $yearFilter = '';
    public $perPage = 10; // Default 10 data per halaman
    
    // Properti Modal Detail
    public $isModalOpen = false;
    public $selectedData = null;

    // Properti Modal Preview PDF
    public $isPdfModalOpen = false;
    public $previewPdfUrl = null;

    // Reset pagination ketika filter diubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingMonthFilter() { $this->resetPage(); }
    public function updatingYearFilter() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

    public function mount()
    {
        // Set default filter tahun ke tahun sekarang
        $this->yearFilter = date('Y');
    }

    public function render()
    {
        // OPTIMASI: Gunakan withCount untuk memperingan load query tabel
        $query = Permohonan::where('user_id', auth()->id())->withCount('items');

        if (!empty($this->search)) {
            $query->where('judul', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        if (!empty($this->monthFilter)) {
            $query->whereMonth('tanggal', $this->monthFilter);
        }

        if (!empty($this->yearFilter)) {
            $query->whereYear('tanggal', $this->yearFilter);
        }

        $riwayat = $query->latest()->paginate($this->perPage);

        // Ambil daftar tahun unik yang ada di database untuk opsi dropdown
        $availableYears = Permohonan::selectRaw('YEAR(tanggal) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        // Jika database kosong, tampilkan tahun saat ini saja
        if($availableYears->isEmpty()){
            $availableYears = collect([date('Y')]);
        }

        return view('livewire.user.riwayat-permohonan', compact('riwayat', 'availableYears'));
    }

    // Modal Detail (Memuat detail item lengkap hanya saat modal dibuka)
    public function showDetail($id)
    {
        $this->selectedData = Permohonan::with('items')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedData = null;
    }

    // Modal Preview PDF
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

    // Fungsi Hapus (Hanya untuk status 'Baru')
    public function delete($id)
    {
        $permohonan = Permohonan::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'Baru') 
            ->first();

        if ($permohonan) {
            if ($permohonan->file_pdf) {
                Storage::disk('public')->delete($permohonan->file_pdf);
            }

            $permohonan->delete();

            $this->closeModal();

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Permohonan berhasil dibatalkan dan dihapus.'
            ]);
        } else {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Gagal menghapus. Data tidak ditemukan atau sudah diproses Admin.'
            ]);
        }
    }
}