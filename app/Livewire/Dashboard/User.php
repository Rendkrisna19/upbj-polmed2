<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
// use App\Models\Permohonan; // Buka komentar ini nanti saat model sudah ada

#[Layout('layouts.app')]
#[Title('Dashboard Unit - UPBJ POLMED')]
class User extends Component
{
    public function render()
    {
        $user = auth()->user();
        $unitName = $user->unit ?? 'Unit Belum Diatur';

     
        $stats = [
            'total_permohonan' => 15,
            'sedang_diproses' => 4,
            'selesai' => 11,
        ];

        // Placeholder 5 permohonan terakhir milik unit ini
        $recentRequests = [
            ['tanggal' => '2026-04-01', 'judul' => 'Pengadaan AC Ruang Rapat', 'status' => 'Diproses', 'color' => 'orange'],
            ['tanggal' => '2026-03-28', 'judul' => 'Pembelian ATK Semester Genap', 'status' => 'Selesai', 'color' => 'emerald'],
            ['tanggal' => '2026-03-15', 'judul' => 'Perbaikan Printer Lab Komputer', 'status' => 'Selesai', 'color' => 'emerald'],
        ];

        return view('livewire.dashboard.user', [
            'unitName' => $unitName,
            'stats' => $stats,
            'recentRequests' => $recentRequests
        ]);
    }
}