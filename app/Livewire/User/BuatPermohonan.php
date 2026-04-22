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
use Illuminate\Support\Facades\Http; // WAJIB TAMBAHKAN INI
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
                // UPDATE DATA
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
                // CREATE DATA BARU
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
            if ($isBaru) {
                // 1. Notifikasi Email ke Semua Admin
                if (Setting::get('is_email_active', true)) {
                    $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
                    if (!empty($adminEmails)) {
                        try {
                            $permohonanLengkap = Permohonan::with(['user', 'unit', 'items', 'dokumenLampirans'])->find($permohonan->id);
                            Mail::to($adminEmails)->send(new NotifikasiPermohonanBaru($permohonanLengkap));
                        } catch (\Exception $e) {
                            // Silent fail
                        }
                    }
                }

                // 2. Notifikasi WhatsApp ke Semua Admin (Fonnte)
                if (Setting::get('is_wa_active', false)) {
                    $adminPhones = User::where('role', 'admin')->whereNotNull('no_hp')->pluck('no_hp')->toArray();
                    $apiToken = Setting::get('wa_api_token');
                    
                    if (!empty($adminPhones) && !empty($apiToken)) {
                        $namaUnit = $unit->nama_unit ?? 'Terkait';
                        $namaUser = auth()->user()->name;
                        $tiket = str_pad($permohonan->id, 5, '0', STR_PAD_LEFT);

                        // Template Pesan untuk Admin
                        $pesan = "🔔 *NOTIFIKASI PERMOHONAN BARU*\n\n";
                        $pesan .= "Halo Admin UPBJ, terdapat pengajuan baru yang masuk ke sistem.\n\n";
                        $pesan .= "🆔 *No. Tiket:* #PRM-{$tiket}\n";
                        $pesan .= "🏢 *Unit:* {$namaUnit}\n";
                        $pesan .= "👤 *Pengaju:* {$namaUser}\n";
                        $pesan .= "📌 *Judul:* {$this->judul}\n";
                        $pesan .= "📅 *Tanggal:* " . date('d/m/Y') . "\n\n";
                        $pesan .= "Silakan login ke dashboard Admin untuk meninjau detail permohonan dan dokumen lampiran.\n\n";
                        $pesan .= " Terima kasih,\n*Sistem UPBJ POLMED*";

                        // Kirim ke Fonnte (Bisa multi-target dipisah koma)
                        try {
                            Http::withHeaders([
                                'Authorization' => $apiToken,
                            ])->post('https://api.fonnte.com/send', [
                                'target' => implode(',', $adminPhones),
                                'message' => $pesan,
                                'countryCode' => '62',
                            ]);
                        } catch (\Exception $e) {
                            // Silent fail
                        }
                    }
                }
            }
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