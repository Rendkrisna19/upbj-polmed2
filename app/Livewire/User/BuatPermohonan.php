<?php

namespace App\Livewire\User;

use App\Models\Permohonan;
use App\Models\ItemPermohonan;
use App\Models\DokumenLampiran; 
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

    // State untuk Multi-Dokumen dan Multi-Item
    public $dokumens = [];
    public $deleted_dokumens = []; // Untuk menampung ID dokumen lama yang dihapus saat Edit
    public $items = [];

    public function mount($id = null)
    {
        if ($id) {
            // Mode EDIT: Panggil relasi items dan dokumenLampirans
            $permohonan = Permohonan::with(['items', 'dokumenLampirans'])
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->where('status', 'Baru') 
                ->firstOrFail();

            $this->permohonan_id = $permohonan->id;
            $this->judul = $permohonan->judul;
            $this->tanggal = $permohonan->tanggal;

            // Load Items
            foreach ($permohonan->items as $item) {
                $this->items[] = [
                    'nama_item' => $item->nama_item,
                    'jumlah' => $item->jumlah
                ];
            }

            // Load Dokumen Lampiran
            foreach ($permohonan->dokumenLampirans as $doc) {
                $this->dokumens[] = [
                    'existing_id'   => $doc->id,
                    'existing_path' => $doc->file_path,
                    'file'          => null,
                    'nama_dokumen'  => $doc->nama_dokumen,
                ];
            }
        } else {
            // Mode CREATE: Siapkan 1 baris kosong untuk Item & Dokumen
            $this->tanggal = date('Y-m-d');
            $this->items[] = ['nama_item' => '', 'jumlah' => 1];
            $this->dokumens[] = ['existing_id' => null, 'existing_path' => null, 'file' => null, 'nama_dokumen' => ''];
        }
    }

    // --- FUNGSI MANAJEMEN ITEM ---
    public function addItem()
    {
        $this->items[] = ['nama_item' => '', 'jumlah' => 1];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    // --- FUNGSI MANAJEMEN DOKUMEN ---
    public function addDokumen()
    {
        $this->dokumens[] = ['existing_id' => null, 'existing_path' => null, 'file' => null, 'nama_dokumen' => ''];
    }

    public function removeDokumen($index)
    {
        // Jika dokumen yang dihapus adalah dokumen yang sudah ada di database, simpan ID-nya
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
            
            // Validasi Array Items
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
            
            // Validasi Array Dokumen (Opsional, tapi jika diisi harus PDF)
            'dokumens.*.file' => 'nullable|mimes:pdf|max:2048',
            'dokumens.*.nama_dokumen' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $unit = Unit::where('nama_unit', auth()->user()->unit)->first();
            $unitId = $unit ? $unit->id : 1;
            $isBaru = false; 

            if ($this->permohonan_id) {
                // 1. UPDATE PERMOHONAN
                $permohonan = Permohonan::find($this->permohonan_id);
                $permohonan->judul = $this->judul;
                $permohonan->tanggal = $this->tanggal;
                $permohonan->save();

                // 2. HAPUS & RE-CREATE ITEMS
                $permohonan->items()->delete();

                // 3. PROSES DOKUMEN YANG DIHAPUS USER
                foreach ($this->deleted_dokumens as $del_id) {
                    $doc = DokumenLampiran::find($del_id);
                    if ($doc) {
                        Storage::disk('public')->delete($doc->file_path); // Hapus file fisik
                        $doc->delete(); // Hapus data di DB
                    }
                }

            } else {
                // 1. CREATE PERMOHONAN BARU
                $permohonan = Permohonan::create([
                    'user_id' => auth()->id(),
                    'unit_id' => $unitId,
                    'judul' => $this->judul,
                    'tanggal' => $this->tanggal,
                    'status' => 'Baru',
                ]);
                $isBaru = true; 
            }

            // SIMPAN SEMUA ITEM
            foreach ($this->items as $item) {
                ItemPermohonan::create([
                    'permohonan_id' => $permohonan->id,
                    'nama_item' => $item['nama_item'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            // SIMPAN SEMUA DOKUMEN LAMPIRAN
            foreach ($this->dokumens as $docData) {
                // Jika ada file baru yang diunggah
                if ($docData['file']) {
                    $path = $docData['file']->store('dokumen_permohonan', 'public');
                    
                    if ($docData['existing_id']) {
                        // Timpa file lama dengan file baru
                        $doc = DokumenLampiran::find($docData['existing_id']);
                        Storage::disk('public')->delete($doc->file_path);
                        $doc->update([
                            'file_path' => $path, 
                            'nama_dokumen' => $docData['nama_dokumen']
                        ]);
                    } else {
                        // Buat data dokumen baru
                        DokumenLampiran::create([
                            'permohonan_id' => $permohonan->id,
                            'file_path' => $path,
                            'nama_dokumen' => $docData['nama_dokumen']
                        ]);
                    }
                } else if ($docData['existing_id']) {
                    // Jika tidak ada file baru, tapi nama dokumen diubah
                    DokumenLampiran::where('id', $docData['existing_id'])
                        ->update(['nama_dokumen' => $docData['nama_dokumen']]);
                }
            }

            DB::commit();

            // PENGIRIMAN NOTIFIKASI EMAIL
            if ($isBaru && Setting::get('is_email_active', true)) {
                $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
                if (!empty($adminEmails)) {
                    try {
                        Mail::to($adminEmails)->send(new NotifikasiPermohonanBaru($permohonan));
                    } catch (\Exception $e) {
                        // Abaikan error email
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