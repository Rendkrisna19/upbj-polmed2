<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Pengaturan Akun - UPBJ POLMED')]
class PengaturanAkun extends Component
{
    use WithFileUploads;

    // Data Profil
    public $name, $email, $unit;
    public $current_photo; // Foto yang ada di database
    public $new_photo;     // Foto baru yang diunggah

    // Data Password
    public $current_password, $new_password, $new_password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->unit = $user->unit;
        $this->current_photo = $user->profile_photo;
    }

    // Fungsi Update Profil & Foto
    public function updateProfile()
    {
        $user = Auth::user();

        // Validasi Dihapus untuk 'unit' karena hanya bisa diedit oleh Super Admin dari halaman kelola pengguna
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'new_photo' => 'nullable|image|max:2048', // Maksimal 2MB
        ]);

        if ($this->new_photo) {
            // Hapus foto lama jika ada
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            // Simpan foto baru
            $path = $this->new_photo->store('profile-photos', 'public');
            $user->profile_photo = $path;
            $this->current_photo = $path; 
        }

        $user->name = $this->name;
        $user->email = $this->email;
        // $user->unit = $this->unit; <--- BARIS INI DIHAPUS AGAR USER TIDAK BISA BYPASS
        $user->save();

        $this->new_photo = null; // Reset input file

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Profil berhasil diperbarui!'
        ]);
    }

    // Fungsi Update Password
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Kata sandi saat ini tidak cocok.');
            return;
        }

        $user->password = Hash::make($this->new_password);
        $user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Kata sandi berhasil diubah!'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pengaturan-akun');
    }
}