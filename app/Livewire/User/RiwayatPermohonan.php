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
    
    // Properti Modal Detail
    public $isModalOpen = false;
    public $selectedData = null;

    // Properti Modal Preview PDF
    public $isPdfModalOpen = false;
    public $previewPdfUrl = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Permohonan::where('user_id', auth()->id())->with('items');

        if (!empty($this->search)) {
            $query->where('judul', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $riwayat = $query->latest()->paginate(10);

        return view('livewire.user.riwayat-permohonan', compact('riwayat'));
    }

    // Modal Detail
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

            // Jika modal detail sedang terbuka saat dihapus, tutup modalnya
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