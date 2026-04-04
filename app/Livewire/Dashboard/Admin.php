<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
// Import model transaksi kamu nanti di sini, contoh:
// use App\Models\Permohonan; 

#[Layout('layouts.app')]
#[Title('Dashboard Admin - UPBJ POLMED')]
class Admin extends Component
{
    public function render()
    {
        // Placeholder data statistik (Nanti hubungkan ke database transaksi)
        $stats = [
            'total_permohonan' => 124,
            'proses_pekerjaan' => 45,
            'realisasi_selesai' => 79,
            'persentase_selesai' => 64
        ];

        return view('livewire.dashboard.admin', [
            'stats' => $stats
        ]);
    }
}