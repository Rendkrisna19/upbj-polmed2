<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads; // WAJIB ditambahkan untuk upload file
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
#[Title('Pengaturan Sistem - UPBJ POLMED')]
class PengaturanSistem extends Component
{
    use WithFileUploads;

    // Menampung inputan dinamis
    public $state = [];
    
    // Menampung file gambar yang baru diupload
    public $new_app_logo;
    public $new_app_favicon;

    public function mount()
    {
        // 1. Nilai Default Bawaan
        $this->state = [
            'app_name'             => 'UPBJ POLMED',
            'app_logo'             => '',
            'app_favicon'          => '',
            'is_email_active'      => true,
            'email_sender_address' => 'pengadaanpolmed@gmail.com',
            'email_sender_name'    => 'UPBJ POLMED',
            'is_wa_active'         => false,
            'wa_api_token'         => '',
        ];

        // 2. Ambil dari database
        $settings = Setting::all();
        
        foreach ($settings as $setting) {
            if ($setting->type === 'boolean') {
                $this->state[$setting->key] = filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            } else {
                if (!empty($setting->value)) {
                    $this->state[$setting->key] = $setting->value;
                }
            }
        }
    }

    // Fungsi khusus untuk menyimpan identitas aplikasi (Branding)
    public function saveBrand()
    {
        $this->validate([
            'state.app_name'  => 'required|string|max:255',
            'new_app_logo'    => 'nullable|image|max:2048', // Maks 2MB
            'new_app_favicon' => 'nullable|image|max:1024', // Maks 1MB
        ]);

        // Simpan Nama Sistem
        Setting::updateOrCreate(
            ['key' => 'app_name'],
            ['group' => 'branding', 'label' => 'Nama Sistem', 'value' => $this->state['app_name'], 'type' => 'string']
        );

        // Simpan Logo Jika Ada Upload Baru
        if ($this->new_app_logo) {
            // Hapus logo lama di storage jika ada
            if (!empty($this->state['app_logo'])) {
                Storage::disk('public')->delete($this->state['app_logo']);
            }
            $path = $this->new_app_logo->store('branding', 'public');
            Setting::updateOrCreate(
                ['key' => 'app_logo'],
                ['group' => 'branding', 'label' => 'Logo Sistem', 'value' => $path, 'type' => 'image']
            );
            $this->state['app_logo'] = $path;
            $this->new_app_logo = null;
        }

        // Simpan Favicon Jika Ada Upload Baru
        if ($this->new_app_favicon) {
            // Hapus favicon lama di storage jika ada
            if (!empty($this->state['app_favicon'])) {
                Storage::disk('public')->delete($this->state['app_favicon']);
            }
            $path = $this->new_app_favicon->store('branding', 'public');
            Setting::updateOrCreate(
                ['key' => 'app_favicon'],
                ['group' => 'branding', 'label' => 'Favicon Sistem', 'value' => $path, 'type' => 'image']
            );
            $this->state['app_favicon'] = $path;
            $this->new_app_favicon = null;
        }

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Identitas Aplikasi berhasil diperbarui!'
        ]);
    }

    // Fungsi untuk menyimpan API & Integrasi
    public function saveGroup($group)
    {
        $keysToSave = [];
        if ($group === 'notifikasi_email') {
            $keysToSave = [
                'is_email_active'      => 'boolean',
                'email_sender_address' => 'string',
                'email_sender_name'    => 'string'
            ];
        } elseif ($group === 'notifikasi_wa') {
            $keysToSave = [
                'is_wa_active' => 'boolean',
                'wa_api_token' => 'password'
            ];
        }

        foreach ($keysToSave as $key => $type) {
            $newValue = $this->state[$key] ?? null;

            if ($type === 'boolean') {
                $newValue = filter_var($newValue, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
            }

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $group,
                    'label' => ucwords(str_replace('_', ' ', $key)),
                    'value' => $newValue,
                    'type'  => $type
                ]
            );
        }

        $this->dispatch('toast', [
            'type' => 'success',
            'message' => 'Pengaturan ' . str_replace('_', ' ', $group) . ' berhasil disimpan!'
        ]);
    }

    public function render()
    {
        return view('livewire.super-admin.pengaturan-sistem');
    }
}