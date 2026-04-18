<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use App\Models\Unit;
use App\Models\Permohonan;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;

#[Layout('layouts.app')]
#[Title('Dashboard Super Admin - UPBJ POLMED')]
class SuperAdmin extends Component
{
    // Properti untuk Filter
    public $filterBulan;
    public $filterTahun;

    public function mount()
    {
        // Set default filter ke bulan dan tahun saat ini
        $this->filterBulan = date('m');
        $this->filterTahun = date('Y');
    }

    // --- LOGIKA WAKTU & SAPAAN DARI BACKEND ---
    public function getGreetingProperty()
    {
        // Set timezone ke Waktu Indonesia Barat
        $hour = Carbon::now('Asia/Jakarta')->format('H');

        if ($hour >= 5 && $hour < 11) {
            return 'Selamat Pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            return 'Selamat Siang';
        } elseif ($hour >= 15 && $hour < 18) {
            return 'Selamat Sore';
        } else {
            return 'Selamat Malam';
        }
    }

    public function getCurrentDateProperty()
    {
        return Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y | H:i \W\I\B');
    }
    // ------------------------------------------

    public function render()
    {
        // 1. Data Statistik Utama
        $totalUsers = User::count();
        $totalUnits = Unit::count();

        // 2. Data berdasarkan Filter Tanggal
        $totalPermohonan = Permohonan::whereMonth('created_at', $this->filterBulan)
            ->whereYear('created_at', $this->filterTahun)
            ->count();

        // 3. Status Sistem Dinamis (Meminjam helper Setting kita sebelumnya)
        $isEmailActive = Setting::get('is_email_active', false);
        $isWaActive = Setting::get('is_wa_active', false);

        // 4. Data Tabel (User yang baru mendaftar)
        // 4. Data Tabel (User yang baru mendaftar)
        $recentUsers = User::latest()
            ->take(5)
            ->get();

        // Ambil daftar tahun unik untuk dropdown filter
        $availableYears = Permohonan::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }

        return view('livewire.dashboard.super-admin', compact(
            'totalUsers',
            'totalUnits',
            'totalPermohonan',
            'isEmailActive',
            'isWaActive',
            'recentUsers',
            'availableYears'
        ));
    }
}
