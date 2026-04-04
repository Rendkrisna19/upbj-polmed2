<?php

namespace App\Livewire\Admin;

use App\Models\Permohonan;
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
    public $isModalOpen = false;
    public $selectedData = null;
    public $isPdfModalOpen = false;
    public $previewPdfUrl = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Permohonan::with(['user', 'unit', 'items'])->where('status', 'Baru');
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhereHas('unit', function($subQ) {
                      $subQ->where('nama_unit', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $permohonanMasuk = $query->latest()->paginate(10);

        return view('livewire.admin.permohonan-masuk', compact('permohonanMasuk'));
    }

    // --- MANAJEMEN MODAL ---
    public function showDetail($id)
    {
        $this->selectedData = Permohonan::with(['user', 'unit', 'items'])
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
        $permohonan = Permohonan::find($id);
        
        if ($permohonan && $permohonan->status == 'Baru') {
            $permohonan->update(['status' => 'Proses']);
            
            $this->closeModal();
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Permohonan berhasil diterima dan masuk ke tahap Proses Pekerjaan.'
            ]);
        }
    }

    public function tolakPermohonan($id)
    {
        $permohonan = Permohonan::find($id);
        
        if ($permohonan && $permohonan->status == 'Baru') {
            $permohonan->update(['status' => 'Ditolak']);
            
            $this->closeModal();
            $this->dispatch('toast', [
                'type' => 'error', // Pakai warna merah/error untuk penolakan
                'message' => 'Permohonan telah ditolak.'
            ]);
        }
    }
}