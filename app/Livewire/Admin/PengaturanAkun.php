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
    public $name, $email, $unit, $no_hp;
    public $country_code = '62'; // Default Indonesia
    public $current_photo; 
    public $new_photo;    

    // Data Password
    public $current_password, $new_password, $new_password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->unit = $user->unit;
        $this->current_photo = $user->profile_photo;

        // Ambil nomor hp, jika sudah ada awalan 62, pisahkan untuk ditampilkan di input
        if ($user->no_hp) {
            if (str_starts_with($user->no_hp, '62')) {
                $this->no_hp = substr($user->no_hp, 2);
            } else {
                $this->no_hp = $user->no_hp;
            }
        }
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'no_hp' => 'required|numeric|min:9', // Wajib diisi
            'new_photo' => 'nullable|image|max:2048', 
        ], [
            'no_hp.required' => 'Nomor WhatsApp wajib diisi untuk menerima notifikasi.',
            'no_hp.numeric'  => 'Nomor HP hanya boleh berisi angka.',
        ]);

        if ($this->new_photo) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $this->new_photo->store('profile-photos', 'public');
            $user->profile_photo = $path;
            $this->current_photo = $path; 
        }

        // Simpan dengan format 62 + nomor (membersihkan angka 0 di depan jika user mengetik 08...)
        $cleanPhone = ltrim($this->no_hp, '0');
        $user->no_hp = $this->country_code . $cleanPhone;

        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        $this->new_photo = null; 

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Profil berhasil diperbarui!'
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
        return view('livewire.admin.pengaturan-akun');
    }
}