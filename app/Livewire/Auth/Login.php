<?php

namespace App\Livewire\Auth;

use App\Models\User; // Pastikan untuk meng-import model User
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.guest')] 
#[Title('Login - UPBJ POLMED')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function mount()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            
            $url = match ($role) {
                'super_admin' => route('super_admin.dashboard'),
                'admin'       => route('admin.dashboard'),
                'user'        => route('user.dashboard'),
                default       => '/',
            };

            return $this->redirect($url); 
        }
    }

    public function authenticate()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Cek apakah email terdaftar di database
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email yang Anda masukkan tidak terdaftar.',
            ]);
        }

        // 2. Jika email terdaftar, cek kecocokan password menggunakan Auth::attempt
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'password' => 'Kata sandi yang Anda masukkan salah.',
            ]);
        }

        session()->regenerate();

        $role = Auth::user()->role;
        $url = match ($role) {
            'super_admin' => route('super_admin.dashboard'),
            'admin'       => route('admin.dashboard'),
            'user'        => route('user.dashboard'),
            default       => '/',
        };

        $this->redirect($url);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}