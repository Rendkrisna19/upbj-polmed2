<?php

namespace App\Livewire\Admin;

use App\Models\Permohonan;
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
    
    // Properti Modal
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
        // Hanya ambil permohonan dengan status 'Proses'
        $query = Permohonan::with(['user', 'unit', 'items'])->where('status', 'Proses');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhereHas('unit', function($subQ) {
                      $subQ->where('nama_unit', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $pekerjaan = $query->latest('updated_at')->paginate(10); // Urutkan berdasarkan waktu disetujui

        return view('livewire.admin.pekerjaan-berjalan', compact('pekerjaan'));
    }

    public function showDetail($id)
    {
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
        $permohonan = Permohonan::find($id);
        
        if ($permohonan && $permohonan->status == 'Proses') {
            $permohonan->update(['status' => 'Selesai']);
            
            $this->closeModal();
            $this->dispatch('toast', [
                'type' => 'success',
                'message' => 'Luar biasa! Pekerjaan telah ditandai Selesai dan dipindah ke Realisasi.'
            ]);
        }
    }
}