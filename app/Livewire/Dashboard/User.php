<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Permohonan; // Memanggil model Permohonan
use Carbon\Carbon;

#[Layout('layouts.app')]
#[Title('Dashboard Unit - UPBJ POLMED')]
class User extends Component
{
    public function render()
    {
        $user = auth()->user();
        // Cek apakah relasi unit ada, jika tidak pakai field biasa
        $unitName = $user->unit->nama_unit ?? $user->unit ?? 'Unit Belum Diatur';

        // 1. Ambil Statistik Riwayat Asli dari Database
        $stats = [
            'total_permohonan' => Permohonan::where('user_id', $user->id)->count(),
            'baru'             => Permohonan::where('user_id', $user->id)->where('status', 'Baru')->count(),
            'sedang_diproses'  => Permohonan::where('user_id', $user->id)->where('status', 'Proses')->count(),
            'selesai'          => Permohonan::where('user_id', $user->id)->where('status', 'Selesai')->count(),
            'ditolak'          => Permohonan::where('user_id', $user->id)->where('status', 'Ditolak')->count(),
        ];

        // 2. Ambil 5 Permohonan Terakhir
        $recentRequests = Permohonan::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // 3. Persiapkan Data untuk Grafik (Chart) Bulan Ini (Jan - Des)
        $chartData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        for ($i = 1; $i <= 12; $i++) {
            $count = Permohonan::where('user_id', $user->id)
                        ->whereYear('tanggal', date('Y'))
                        ->whereMonth('tanggal', $i)
                        ->count();
            $chartData[] = $count;
        }

        return view('livewire.dashboard.user', compact('unitName', 'stats', 'recentRequests', 'chartData', 'months'));
    }
}