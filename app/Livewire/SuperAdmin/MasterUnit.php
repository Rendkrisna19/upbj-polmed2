<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Unit;

// INI WAJIB DITAMBAHKAN AGAR ATTRIBUTE TERBACA
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Master Unit - UPBJ POLMED')]
class MasterUnit extends Component
{
    use WithPagination; // Aktifkan pagination Livewire

    public $search = '';
    public $unit_id, $nama_unit;
    public $isModalOpen = false;
    public $modalTitle = 'Tambah Unit Baru';

    // Reset halaman ke-1 setiap kali user mengetik di kolom pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        $units = Unit::where('nama_unit', 'like', $searchTerm)
                     ->orderBy('id', 'desc')
                     ->paginate(10); // Menampilkan 10 data per halaman

        // Hapus ->title() di sini, karena sudah ditangani oleh attribute #[Title] di atas
        return view('livewire.super-admin.master-unit', [
            'units' => $units
        ]);
    }

    public function create()
    {
        $this->resetFields();
        $this->modalTitle = 'Tambah Unit Baru';
        $this->isModalOpen = true;
    }

    public function store()
    {
        // Validasi input
        $this->validate([
            'nama_unit' => 'required|unique:tb_unit,nama_unit,' . $this->unit_id,
        ], [
            'nama_unit.required' => 'Nama unit wajib diisi.',
            'nama_unit.unique' => 'Unit dengan nama ini sudah terdaftar.'
        ]);

        // Simpan data (Bisa untuk Create maupun Update)
        Unit::updateOrCreate(
            ['id' => $this->unit_id],
            ['nama_unit' => $this->nama_unit]
        );

        $this->closeModal();
        
        // Kirim notifikasi ke SweetAlert2 di layout utama
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => $this->unit_id ? 'Data unit berhasil diperbarui!' : 'Unit baru berhasil ditambahkan!'
        ]);
    }

    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->unit_id = $id;
        $this->nama_unit = $unit->nama_unit;
        $this->modalTitle = 'Edit Data Unit';
        $this->isModalOpen = true;
    }

    public function delete($id)
    {
        Unit::findOrFail($id)->delete();
        
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Data unit berhasil dihapus!'
        ]);
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->unit_id = null;
        $this->nama_unit = '';
        $this->resetValidation();
    }
}