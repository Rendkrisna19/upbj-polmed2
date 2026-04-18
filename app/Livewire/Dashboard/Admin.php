<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Permohonan; 
use Carbon\Carbon;

#[Layout('layouts.app')]
#[Title('Dashboard Admin - UPBJ POLMED')]
class Admin extends Component
{
    // Filter Dinamis
    public $filterBulan;
    public $filterTahun;

    public function mount()
    {
        // Set default filter ke bulan dan tahun berjalan
        $this->filterBulan = date('m');
        $this->filterTahun = date('Y');
    }

    public function render()
    {
        // 1. Base Query Statistik berdasarkan Filter
        $baseQuery = Permohonan::whereMonth('tanggal', $this->filterBulan)
                               ->whereYear('tanggal', $this->filterTahun);

        $totalPermohonan = (clone $baseQuery)->count();
        $prosesPekerjaan = (clone $baseQuery)->where('status', 'Proses')->count();
        $realisasiSelesai = (clone $baseQuery)->where('status', 'Selesai')->count();
        
        // Hitung persentase selesai (Hindari pembagian dengan nol)
        $persentaseSelesai = $totalPermohonan > 0 
            ? round(($realisasiSelesai / $totalPermohonan) * 100) 
            : 0;

        $stats = [
            'total_permohonan'   => $totalPermohonan,
            'proses_pekerjaan'   => $prosesPekerjaan,
            'realisasi_selesai'  => $realisasiSelesai,
            'persentase_selesai' => $persentaseSelesai
        ];

        // 2. Ambil 5 Permohonan Terakhir (Terbaru) berdasarkan filter
        $recentPermohonan = (clone $baseQuery)
                            ->with('unit') // Panggil relasi unit
                            ->latest()
                            ->take(5)
                            ->get();

        // 3. Ambil daftar tahun unik untuk filter
        $availableYears = Permohonan::selectRaw('YEAR(tanggal) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        if($availableYears->isEmpty()){
            $availableYears = collect([date('Y')]);
        }

        return view('livewire.dashboard.admin', compact('stats', 'recentPermohonan', 'availableYears'));
    }
}