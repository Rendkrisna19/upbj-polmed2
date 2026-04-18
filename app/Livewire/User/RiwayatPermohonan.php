<?php

namespace App\Livewire\User;

use App\Models\Permohonan;
use App\Models\DokumenLampiran;
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
    public $perPage = 10; 
    
    public $isModalOpen = false;
    public $selectedData = null;

    public $isPdfModalOpen = false;
    public $previewPdfUrl = null;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingMonthFilter() { $this->resetPage(); }
    public function updatingYearFilter() { $this->resetPage(); }
    public function updatingPerPage() { $this->resetPage(); }

    public function mount()
    {
        $this->yearFilter = date('Y');
    }

    public function render()
    {
        // 1. BASE QUERY
        $query = Permohonan::where('user_id', auth()->id())->withCount('items');

        // 2. APPLY FILTERS
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

        // 3. GET PAGINATED DATA
        $riwayat = $query->latest()->paginate($this->perPage);

        // 4. LOGIC STATISTIK (Dihitung berdasarkan filter yang sedang aktif)
        // Kita copy kondisi filter agar hitungannya akurat dengan apa yang tampil di tabel
        $statsQuery = Permohonan::where('user_id', auth()->id());
        if (!empty($this->search)) $statsQuery->where('judul', 'like', '%' . $this->search . '%');
        if (!empty($this->monthFilter)) $statsQuery->whereMonth('tanggal', $this->monthFilter);
        if (!empty($this->yearFilter)) $statsQuery->whereYear('tanggal', $this->yearFilter);

        $stats = [
            'total'   => (clone $statsQuery)->count(),
            'baru'    => (clone $statsQuery)->where('status', 'Baru')->count(),
            'proses'  => (clone $statsQuery)->where('status', 'Proses')->count(),
            'selesai' => (clone $statsQuery)->where('status', 'Selesai')->count(),
            'ditolak' => (clone $statsQuery)->where('status', 'Ditolak')->count(),
        ];

        // 5. GET AVAILABLE YEARS
        $availableYears = Permohonan::selectRaw('YEAR(tanggal) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        if($availableYears->isEmpty()){
            $availableYears = collect([date('Y')]);
        }

        return view('livewire.user.riwayat-permohonan', compact('riwayat', 'availableYears', 'stats'));
    }

    public function showDetail($id)
    {
        // Panggil relasi items dan dokumenLampirans sekaligus
        $this->selectedData = Permohonan::with(['items', 'dokumenLampirans'])
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

    public function delete($id)
    {
        // Pastikan hanya permohonan 'Baru' yang bisa dihapus
        $permohonan = Permohonan::with('dokumenLampirans')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'Baru') 
            ->first();

        if ($permohonan) {
            // Hapus semua file fisik dokumen lampiran terkait
            foreach ($permohonan->dokumenLampirans as $doc) {
                Storage::disk('public')->delete($doc->file_path);
            }

            // Hapus data permohonan (karena pakai onDelete Cascade, data tabel lampiran otomatis terhapus)
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