<?php

namespace App\Livewire\SuperAdmin;

use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Pengaturan Sistem - UPBJ POLMED')]
class PengaturanSistem extends Component
{
    // Menampung semua inputan secara dinamis
    public $state = [];

    public function mount()
    {
        // 1. Nilai Default Bawaan (Otomatis terisi jika di database kosong)
        $this->state = [
            'is_email_active'      => true, // Default ON
            'email_sender_address' => 'pengadaanpolmed@gmail.com',
            'email_sender_name'    => 'UPBJ POLMED',
            'is_wa_active'         => false, // Default OFF
            'wa_api_token'         => '',
        ];

        // 2. Ambil semua pengaturan dari database untuk menimpa default
        $settings = Setting::all();
        
        foreach ($settings as $setting) {
            if ($setting->type === 'boolean') {
                $this->state[$setting->key] = filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            } else {
                // Hanya timpa jika nilainya di database tidak kosong
                if (!empty($setting->value)) {
                    $this->state[$setting->key] = $setting->value;
                }
            }
        }
    }

    public function saveGroup($group)
    {
        // 3. Definisi Form (Supaya jika data di database kosong, tetap bisa di-Save)
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

        // 4. Update atau Create data ke database
        foreach ($keysToSave as $key => $type) {
            $newValue = $this->state[$key] ?? null;

            if ($type === 'boolean') {
                $newValue = filter_var($newValue, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
            }

            Setting::updateOrCreate(
                ['key' => $key], // Cari berdasarkan key
                [
                    'group' => $group,
                    'label' => ucwords(str_replace('_', ' ', $key)),
                    'value' => $newValue,
                    'type'  => $type
                ]
            );
        }

        // Tampilkan notifikasi sukses
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