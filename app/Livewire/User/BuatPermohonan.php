<?php

namespace App\Livewire\User;

use App\Models\Permohonan;
use App\Models\ItemPermohonan;
use App\Models\Unit;
use App\Models\Setting; 
use App\Models\User;    
use App\Mail\NotifikasiPermohonanBaru; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form Permohonan - UPBJ POLMED')]
class BuatPermohonan extends Component
{
    use WithFileUploads;

    public $permohonan_id; 
    public $judul;
    public $tanggal;
    public $file_pdf;
    public $existing_pdf; 

    public $items = [];

    public function mount($id = null)
    {
        if ($id) {
            $permohonan = Permohonan::with('items')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->where('status', 'Baru') 
                ->firstOrFail();

            $this->permohonan_id = $permohonan->id;
            $this->judul = $permohonan->judul;
            $this->tanggal = $permohonan->tanggal;
            $this->existing_pdf = $permohonan->file_pdf;

            foreach ($permohonan->items as $item) {
                $this->items[] = [
                    'nama_item' => $item->nama_item,
                    'jumlah' => $item->jumlah
                ];
            }
        } else {
            $this->tanggal = date('Y-m-d');
            $this->items[] = ['nama_item' => '', 'jumlah' => 1];
        }
    }

    public function addItem()
    {
        $this->items[] = ['nama_item' => '', 'jumlah' => 1];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function store()
    {
        // Ubah validasi file menjadi nullable (Tidak wajib)
        $this->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'file_pdf' => 'nullable|mimes:pdf|max:2048', // Opsional
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $unit = Unit::where('nama_unit', auth()->user()->unit)->first();
            $unitId = $unit ? $unit->id : 1;
            
            $isBaru = false; 

            if ($this->permohonan_id) {
                // UPDATE DATA
                $permohonan = Permohonan::find($this->permohonan_id);
                $permohonan->judul = $this->judul;
                $permohonan->tanggal = $this->tanggal;

                // Jika user mengunggah file baru, hapus yang lama & timpa
                if ($this->file_pdf) {
                    if ($permohonan->file_pdf) {
                        Storage::disk('public')->delete($permohonan->file_pdf);
                    }
                    $permohonan->file_pdf = $this->file_pdf->store('dokumen_permohonan', 'public');
                }
                $permohonan->save();

                $permohonan->items()->delete();

            } else {
                // CREATE DATA BARU
                // Jika user mengunggah file, simpan pathnya, jika tidak, biarkan null
                $filePath = $this->file_pdf ? $this->file_pdf->store('dokumen_permohonan', 'public') : null;

                $permohonan = Permohonan::create([
                    'user_id' => auth()->id(),
                    'unit_id' => $unitId,
                    'judul' => $this->judul,
                    'tanggal' => $this->tanggal,
                    'file_pdf' => $filePath,
                    'status' => 'Baru',
                ]);
                
                $isBaru = true; 
            }

            foreach ($this->items as $item) {
                ItemPermohonan::create([
                    'permohonan_id' => $permohonan->id,
                    'nama_item' => $item['nama_item'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            DB::commit();

            // PENGIRIMAN NOTIFIKASI EMAIL
            if ($isBaru) {
                if (Setting::get('is_email_active', true)) {
                    $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
                    
                    if (!empty($adminEmails)) {
                        try {
                            Mail::to($adminEmails)->send(new NotifikasiPermohonanBaru($permohonan));
                        } catch (\Exception $e) {
                            // Abaikan error email
                        }
                    }
                }
            }

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => $this->permohonan_id ? 'Permohonan berhasil diperbarui!' : 'Permohonan berhasil dikirim!'
            ]);

            return $this->redirect(route('user.riwayat_status'), navigate: true);

        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatch('toast', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $suggestions = ItemPermohonan::select('nama_item')->distinct()->pluck('nama_item');
        return view('livewire.user.buat-permohonan', compact('suggestions'));
    }
}