<?php

namespace App\Livewire\Admin;

use App\Models\Permohonan; // Pastikan model ini sudah kamu buat
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Realisasi Selesai - UPBJ POLMED')]
class RealisasiSelesai extends Component
{
    use WithPagination;

    public $search = '';
    
    // Properti untuk Modal Detail
    public $isDetailModalOpen = false;
    public $detailData = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Fungsi untuk melihat detail rincian barang
    public function showDetail($id)
    {
        // Mengambil data permohonan beserta relasi user pengirim dan item barangnya
        $this->detailData = Permohonan::with(['user', 'items'])->findOrFail($id);
        $this->isDetailModalOpen = true;
    }

    public function closeModal()
    {
        $this->isDetailModalOpen = false;
        $this->detailData = null;
    }

    public function render()
    {
        /* * Asumsi Struktur Database:
         * Model Permohonan berelasi dengan User (belongsTo).
         * Status difilter hanya yang 'selesai'.
         */
        $realisasi = Permohonan::with('user')
            ->where('status', 'selesai')
            ->when($this->search, function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhere('tanggal', 'like', '%' . $this->search . '%')
                      ->orWhereHas('user', function ($q) {
                          $q->where('unit', 'like', '%' . $this->search . '%')
                            ->orWhere('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.realisasi-selesai', [
            'realisasi' => $realisasi
        ]);
    }
}