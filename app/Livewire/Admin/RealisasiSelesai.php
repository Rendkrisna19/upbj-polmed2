<?php

namespace App\Livewire\Admin;

use App\Models\Permohonan;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
#[Title('Realisasi Selesai - Admin UPBJ')]
class RealisasiSelesai extends Component
{
    use WithPagination;

    public $search = '';
    public $monthFilter = '';
    public $yearFilter = '';
    public $perPage = 10;
    
    // Properti Modal Detail
    public $isDetailModalOpen = false;
    public $detailData = null;

    // Properti Modal PDF
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
        // Gunakan withCount('items') agar lebih ringan saat load tabel
        $query = Permohonan::with(['user', 'unit'])->withCount('items')->where('status', 'Selesai');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhereHas('unit', function($subQ) {
                      $subQ->where('nama_unit', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('user', function($subQ) {
                      $subQ->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter berdasarkan waktu diselesaikan (updated_at)
        if (!empty($this->monthFilter)) {
            $query->whereMonth('updated_at', $this->monthFilter);
        }

        if (!empty($this->yearFilter)) {
            $query->whereYear('updated_at', $this->yearFilter);
        }

        $realisasi = $query->latest('updated_at')->paginate($this->perPage);

        // Ambil daftar tahun unik dari data yang sudah selesai
        $availableYears = Permohonan::where('status', 'Selesai')
                            ->selectRaw('YEAR(updated_at) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        if($availableYears->isEmpty()){
            $availableYears = collect([date('Y')]);
        }

        return view('livewire.admin.realisasi-selesai', compact('realisasi', 'availableYears'));
    }

    // --- MANAJEMEN MODAL ---
    public function showDetail($id)
    {
        // Ambil data lengkap beserta itemnya hanya saat modal dibuka
        $this->detailData = Permohonan::with(['user', 'unit', 'items'])
            ->where('id', $id)
            ->where('status', 'Selesai')
            ->firstOrFail();
            
        $this->isDetailModalOpen = true;
    }

    public function closeModal()
    {
        $this->isDetailModalOpen = false;
        $this->detailData = null;
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
}