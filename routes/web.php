<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Livewire\Auth\Login;
use App\Livewire\Dashboard\SuperAdmin;
use App\Livewire\SuperAdmin\KelolaUser;
use App\Livewire\SuperAdmin\MasterUnit;
use App\Livewire\Dashboard\Admin;
use App\Livewire\Dashboard\User;
use App\Livewire\User\BuatPermohonan;
use App\Livewire\User\RiwayatPermohonan;
use App\Livewire\Admin\PermohonanMasuk;
use App\Livewire\Admin\PekerjaanBerjalan;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes UPBJ POLMED
|--------------------------------------------------------------------------
*/

Route::get('/setup-storage', function () {
    Artisan::call('storage:link');
    return 'Storage berhasil dilink!';
});

// Rute Publik (Guest) - Diarahkan ke komponen Login Livewire
Route::get('/', Login::class)->name('login');
Route::get('/login', Login::class);

// ==========================================
// AREA MIDDLEWARE AUTH (WAJIB LOGIN)
// ==========================================
Route::middleware('auth')->group(function () {

    // Global Logout
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');


    // ------------------------------------------
    // ROUTES SUPER ADMIN
    // ------------------------------------------
    Route::middleware('role:super_admin')->prefix('super-admin')->group(function () {
        Route::get('/dashboard', SuperAdmin::class)->name('super_admin.dashboard');
        Route::get('/users', KelolaUser::class)->name('users.index');
        Route::get('/unit', MasterUnit::class)->name('unit.index');
    });


    // ------------------------------------------
    // ROUTES ADMIN
    // ------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::view('/dashboard', 'dashboard.admin')->name('admin.dashboard');
        Route::get('/dashboard', Admin::class)->name('admin.dashboard');
        Route::get('/permohonan-masuk', PermohonanMasuk::class)->name('admin.permohonan_masuk');
        Route::get('/pekerjaan-berjalan', PekerjaanBerjalan::class)->name('admin.pekerjaan_berjalan');
    });


    // ------------------------------------------
    // ROUTES USER (UNIT)
    // ------------------------------------------
    Route::middleware('role:user')->prefix('unit')->group(function () {
        Route::view('/dashboard', 'dashboard.user')->name('user.dashboard');
        Route::get('/dashboard', User::class)->name('user.dashboard');
        Route::get('/buat-permohonan/{id?}', App\Livewire\User\BuatPermohonan::class)->name('user.buat_permohonan');
        Route::get('/riwayat-status', RiwayatPermohonan::class)->name('user.riwayat_status');
    });
});
