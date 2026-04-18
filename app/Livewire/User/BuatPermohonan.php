<?php

namespace App\Livewire\User;

use App\Models\Permohonan;
use App\Models\ItemPermohonan;
use App\Models\DokumenLampiran;
use App\Models\Unit;
use App\Models\Setting; 
use App\Models\User;    
use App\Jobs\KirimEmailPermohonanBaru; // Panggil Job
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Mail;

#[Layout('layouts.app')]
#[Title('Form Permohonan - UPBJ POLMED')]
class BuatPermohonan extends Component
{
    use WithFileUploads;

    public $permohonan_id; 
    public $judul;
    public $tanggal;

    public $dokumens = [];
    public $deleted_dokumens = []; 
    public $items = [];

    public function mount($id = null)
    {
        if ($id) {
            $permohonan = Permohonan::with(['items', 'dokumenLampirans'])
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->where('status', 'Baru') 
                ->firstOrFail();

            $this->permohonan_id = $permohonan->id;
            $this->judul = $permohonan->judul;
            $this->tanggal = $permohonan->tanggal;

            foreach ($permohonan->items as $item) {
                $this->items[] = [
                    'nama_item' => $item->nama_item,
                    'jumlah' => $item->jumlah
                ];
            }

            foreach ($permohonan->dokumenLampirans as $doc) {
                $this->dokumens[] = [
                    'existing_id'   => $doc->id,
                    'existing_path' => $doc->file_path,
                    'file'          => null,
                    'nama_dokumen'  => $doc->nama_dokumen,
                ];
            }
        } else {
            $this->tanggal = date('Y-m-d');
            $this->items[] = ['nama_item' => '', 'jumlah' => 1];
            $this->dokumens[] = ['existing_id' => null, 'existing_path' => null, 'file' => null, 'nama_dokumen' => ''];
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

    public function addDokumen()
    {
        $this->dokumens[] = ['existing_id' => null, 'existing_path' => null, 'file' => null, 'nama_dokumen' => ''];
    }

    public function removeDokumen($index)
    {
        if (!empty($this->dokumens[$index]['existing_id'])) {
            $this->deleted_dokumens[] = $this->dokumens[$index]['existing_id'];
        }
        unset($this->dokumens[$index]);
        $this->dokumens = array_values($this->dokumens);
    }

    public function store()
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            'dokumens.*.file' => 'nullable|mimes:pdf|max:2048',
            'dokumens.*.nama_dokumen' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $unit = Unit::where('nama_unit', auth()->user()->unit)->first();
            $unitId = $unit ? $unit->id : 1;
            $isBaru = false; 

            if ($this->permohonan_id) {
                // UPDATE PERMOHONAN
                $permohonan = Permohonan::find($this->permohonan_id);
                $permohonan->judul = $this->judul;
                $permohonan->tanggal = $this->tanggal;
                $permohonan->save();

                $permohonan->items()->delete();

                foreach ($this->deleted_dokumens as $del_id) {
                    $doc = DokumenLampiran::find($del_id);
                    if ($doc) {
                        Storage::disk('public')->delete($doc->file_path);
                        $doc->delete(); 
                    }
                }

            } else {
                // CREATE PERMOHONAN BARU
                $permohonan = Permohonan::create([
                    'user_id' => auth()->id(),
                    'unit_id' => $unitId,
                    'judul' => $this->judul,
                    'tanggal' => $this->tanggal,
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

            foreach ($this->dokumens as $docData) {
                if ($docData['file']) {
                    $path = $docData['file']->store('dokumen_permohonan', 'public');
                    
                    if ($docData['existing_id']) {
                        $doc = DokumenLampiran::find($docData['existing_id']);
                        Storage::disk('public')->delete($doc->file_path);
                        $doc->update([
                            'file_path' => $path, 
                            'nama_dokumen' => $docData['nama_dokumen']
                        ]);
                    } else {
                        DokumenLampiran::create([
                            'permohonan_id' => $permohonan->id,
                            'file_path' => $path,
                            'nama_dokumen' => $docData['nama_dokumen']
                        ]);
                    }
                } else if ($docData['existing_id']) {
                    DokumenLampiran::where('id', $docData['existing_id'])
                        ->update(['nama_dokumen' => $docData['nama_dokumen']]);
                }
            }

            DB::commit();

            // ==============================================================
            // LOGIKA NOTIFIKASI DINAMIS (EMAIL & WHATSAPP) HANYA JIKA BARU
            // ==============================================================
            // ==============================================================
            // LOGIKA NOTIFIKASI DINAMIS (EMAIL & WHATSAPP) HANYA JIKA BARU
            // ==============================================================
            if ($isBaru) {
                
                // 1. Notifikasi Email (Dikirim langsung / Synchronous)
                if (Setting::get('is_email_active', true)) {
                    $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
                    
                    if (!empty($adminEmails)) {
                        try {
                            // Query relasi lengkap agar PDF ikut terlampir di email
                            $permohonanLengkap = Permohonan::with(['user', 'unit', 'items', 'dokumenLampirans'])->find($permohonan->id);
                            
                            // Kirim langsung (Layar akan loading 3-5 detik di sini)
                            Mail::to($adminEmails)->send(new \App\Mail\NotifikasiPermohonanBaru($permohonanLengkap));
                        } catch (\Exception $e) {
                            // Silent fail: Abaikan jika gagal agar data tetap tersimpan
                        }
                    }
                }

                // 2. Wadah Logika Notifikasi WhatsApp Gateway (Fonnte)
                if (Setting::get('is_wa_active', false)) {
                    $adminPhones = User::where('role', 'admin')->whereNotNull('no_hp')->pluck('no_hp')->toArray();
                    $apiToken = Setting::get('wa_api_token');
                    
                    if (!empty($adminPhones) && !empty($apiToken)) {
                        // $pesan = "Halo Admin UPBJ, terdapat permohonan baru dari Unit " . ($unit->nama_unit ?? 'Terkait') . " dengan judul: " . $this->judul;
                        // Logika cURL ke Fonnte
                    }
                }
            }
            // ==============================================================
            // ==============================================================

            $this->dispatch('toast', [
                'type' => 'success',
                'message' => $this->permohonan_id ? 'Permohonan berhasil diperbarui!' : 'Permohonan berhasil dikirim!'
            ]);

            return redirect()->route('user.riwayat_status');

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