<?php

namespace App\Livewire\Auth;

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

            // PERBAIKAN: Hapus parameter navigate: true
            return $this->redirect($url); 
        }
    }

    public function authenticate()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
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

        // PERBAIKAN: Hapus parameter navigate: true di sini juga
        $this->redirect($url);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}