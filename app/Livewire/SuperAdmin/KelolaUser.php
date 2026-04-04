<?php

namespace App\Livewire\SuperAdmin;

use App\Models\User;
use App\Models\Unit; // Wajib memanggil model Unit
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

// Wajib dipanggil untuk Livewire 3
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Kelola User - UPBJ POLMED')]
class KelolaUser extends Component
{
    use WithPagination;

    // Properti untuk Pencarian
    public $search = '';

    // Properti untuk Form Input
    public $userId, $name, $email, $unit, $role = 'user', $password;
    
    // Properti Modal State
    public $isModalOpen = false;
    public $isEditMode = false;

    // Reset pagination ketika melakukan pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Panggil data user dengan pencarian
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('unit', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Panggil data dari tb_unit untuk dropdown
        $unitsList = Unit::orderBy('nama_unit', 'asc')->get();

        return view('livewire.super-admin.kelola-user', compact('users', 'unitsList'));
    }

    // Membuka Modal untuk Tambah Data
    public function create()
    {
        $this->resetFields();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    // Membuka Modal untuk Edit Data
    public function edit($id)
    {
        $this->resetFields();
        $this->isEditMode = true;
        
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->unit = $user->unit;
        $this->role = $user->role;
        
        $this->isModalOpen = true;
    }

    // Menyimpan Data (Create & Update)
    public function store()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required|in:super_admin,admin,user',
            'unit' => 'nullable|string|max:255',
        ];

        // Password wajib diisi saat mode tambah (create), tapi opsional saat edit
        if (!$this->isEditMode) {
            $rules['password'] = 'required|min:8';
        } else {
            $rules['password'] = 'nullable|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            // Logika: Jika admin/super_admin, unit otomatis jadi null
            'unit' => ($this->role === 'super_admin' || $this->role === 'admin') ? null : $this->unit,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $data);

        $this->closeModal();

        // Memicu Toast SweetAlert2
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => $this->isEditMode ? 'Data pengguna berhasil diperbarui!' : 'Pengguna baru berhasil ditambahkan!'
        ]);
    }

    // Menghapus Data
    public function delete($id)
    {
        // Proteksi cegah hapus diri sendiri
        if (auth()->id() == $id) {
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri!'
            ]);
            return;
        }

        User::findOrFail($id)->delete();
        
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Pengguna berhasil dihapus!'
        ]);
    }

    // Menutup Modal & Reset Input
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetFields();
        $this->resetValidation();
    }

    public function resetFields()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->unit = '';
        $this->role = 'user';
        $this->password = '';
    }
}