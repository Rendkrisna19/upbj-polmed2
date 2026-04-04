<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

// Memanggil layout utama yang berisi Sidebar & Header tadi
#[Layout('layouts.app')] 
#[Title('Dashboard Super Admin')] // Otomatis mengisi tag <title>
class SuperAdmin extends Component
{
    // Nanti di sini kamu bisa lempar data dinamis dari database, 
    // misalnya $totalUsers = User::count();

    public function render()
    {
        return view('livewire.dashboard.super-admin');
    }
}