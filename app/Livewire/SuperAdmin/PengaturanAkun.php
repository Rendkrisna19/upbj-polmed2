<?php

namespace App\Livewire\SuperAdmin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Pengaturan Akun Super Admin - UPBJ POLMED')]
class PengaturanAkun extends Component
{
    use WithFileUploads;

    public $name, $email;
    public $current_photo;
    public $new_photo;

    public $current_password, $new_password, $new_password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->current_photo = $user->profile_photo;
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'new_photo' => 'nullable|image|max:2048', 
        ]);

        if ($this->new_photo) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $this->new_photo->store('profile-photos', 'public');
            $user->profile_photo = $path;
            $this->current_photo = $path; 
        }

        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        $this->new_photo = null; 

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Profil Super Admin berhasil diperbarui!'
        ]);
    }

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
        return view('livewire.super-admin.pengaturan-akun');
    }
}