<?php

namespace App\Livewire\User;

use App\Models\Permohonan;
use App\Models\ItemPermohonan;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Form Permohonan - UPBJ POLMED')]
class BuatPermohonan extends Component
{
    use WithFileUploads;

    public $permohonan_id; // Simpan ID jika mode edit
    public $judul;
    public $tanggal;
    public $file_pdf;
    public $existing_pdf; // Untuk menyimpan path PDF lama

    public $items = [];

    public function mount($id = null)
    {
        if ($id) {
            // Mode EDIT: Ambil data dari database
            $permohonan = Permohonan::with('items')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->where('status', 'Baru') // Proteksi
                ->firstOrFail();

            $this->permohonan_id = $permohonan->id;
            $this->judul = $permohonan->judul;
            $this->tanggal = $permohonan->tanggal;
            $this->existing_pdf = $permohonan->file_pdf;

            // Masukkan item dari database ke dalam array dinamis
            foreach ($permohonan->items as $item) {
                $this->items[] = [
                    'nama_item' => $item->nama_item,
                    'jumlah' => $item->jumlah
                ];
            }
        } else {
            // Mode TAMBAH BARU
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
        // Jika mode edit, file PDF boleh kosong (artinya tidak ingin mengganti file lama)
        $pdfRule = $this->permohonan_id ? 'nullable|mimes:pdf|max:2048' : 'required|mimes:pdf|max:2048';

        $this->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'file_pdf' => $pdfRule,
            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:255',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $unit = Unit::where('nama_unit', auth()->user()->unit)->first();
            $unitId = $unit ? $unit->id : 1;

            if ($this->permohonan_id) {
                // UPDATE DATA
                $permohonan = Permohonan::find($this->permohonan_id);
                $permohonan->judul = $this->judul;
                $permohonan->tanggal = $this->tanggal;

                // Jika user upload PDF baru, hapus yang lama, lalu simpan yang baru
                if ($this->file_pdf) {
                    if ($permohonan->file_pdf) Storage::disk('public')->delete($permohonan->file_pdf);
                    $permohonan->file_pdf = $this->file_pdf->store('dokumen_permohonan', 'public');
                }
                $permohonan->save();

                // Hapus semua rincian item lama, ganti dengan susunan array yang baru
                $permohonan->items()->delete();

            } else {
                // CREATE DATA BARU
                $filePath = $this->file_pdf->store('dokumen_permohonan', 'public');

                $permohonan = Permohonan::create([
                    'user_id' => auth()->id(),
                    'unit_id' => $unitId,
                    'judul' => $this->judul,
                    'tanggal' => $this->tanggal,
                    'file_pdf' => $filePath,
                    'status' => 'Baru',
                ]);
            }

            // Simpan detail item (Berlaku untuk Create maupun Update)
            foreach ($this->items as $item) {
                ItemPermohonan::create([
                    'permohonan_id' => $permohonan->id,
                    'nama_item' => $item['nama_item'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            DB::commit();

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