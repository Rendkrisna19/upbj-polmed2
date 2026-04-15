<div class="w-full pb-12">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                {{ $permohonan_id ? 'Edit Permohonan Pengadaan' : 'Form Permohonan Baru' }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $permohonan_id ? 'Perbarui informasi dan rincian dokumen pengajuan Anda di bawah ini.' : 'Lengkapi informasi di bawah ini beserta rincian daftar barang/jasa yang dibutuhkan unit Anda.' }}
            </p>
        </div>
        
        <a href="{{ route('user.riwayat_status') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <datalist id="item-suggestions">
        @foreach($suggestions as $sug)
            <option value="{{ $sug }}">
        @endforeach
    </datalist>

    <form wire:submit.prevent="store" class="space-y-6">
        
        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-800">
            <div class="flex items-center mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center mr-3">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">1. Informasi Surat Pengajuan</h2>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Judul Permohonan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <i class="fa-solid fa-heading text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" wire:model="judul" class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 pl-10 py-2.5 text-sm transition-colors shadow-sm" placeholder="Misal: Pengadaan AC untuk Ruang Lab Komputer">
                    </div>
                    @error('judul') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Surat <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="tanggal" class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 py-2.5 px-3.5 text-sm transition-colors shadow-sm">
                    @error('tanggal') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Dokumen Pendukung (PDF) 
                        <span class="text-gray-400 font-normal text-xs ml-1">(Opsional)</span>
                    </label>
                    
                    @if($file_pdf)
                        <div class="flex items-center justify-between p-3.5 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
                            <div class="flex items-center overflow-hidden">
                                <i class="fa-solid fa-file-pdf text-red-500 text-xl mr-3 shrink-0"></i>
                                <div class="truncate">
                                    <p class="text-sm font-semibold text-purple-900 dark:text-purple-100 truncate">{{ $file_pdf->getClientOriginalName() }}</p>
                                    <p class="text-xs text-purple-600 dark:text-purple-400">Siap untuk dikirim</p>
                                </div>
                            </div>
                            <button type="button" wire:click="$set('file_pdf', null)" class="text-red-500 hover:text-red-700 p-2 shrink-0 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    @elseif($existing_pdf)
                        <div class="space-y-3">
                            <div class="w-full h-48 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden relative group bg-gray-50 dark:bg-gray-800">
                                <iframe src="{{ Storage::url($existing_pdf) }}" class="w-full h-full object-cover"></iframe>
                                <div class="absolute inset-0 bg-gray-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <a href="{{ Storage::url($existing_pdf) }}" target="_blank" class="px-4 py-2 bg-white text-gray-900 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-100 transition-colors">
                                        <i class="fa-solid fa-arrow-up-right-from-square mr-1.5"></i> Buka Penuh
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <i class="fa-solid fa-check-circle text-green-500 mr-2"></i> Dokumen tersimpan
                                </div>
                                <label class="cursor-pointer text-sm font-medium text-purple-600 dark:text-purple-400 hover:text-purple-700 hover:underline">
                                    <input type="file" wire:model="file_pdf" accept="application/pdf" class="hidden">
                                    Ganti Dokumen
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="relative w-full bg-gray-50 dark:bg-gray-800/50 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg flex items-center justify-center p-3 hover:bg-purple-50 dark:hover:bg-purple-900/10 hover:border-purple-400 transition-colors cursor-pointer">
                            <input type="file" wire:model="file_pdf" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="flex items-center space-x-2 pointer-events-none text-gray-500 dark:text-gray-400">
                                <i class="fa-solid fa-cloud-arrow-up text-purple-600 dark:text-purple-400 text-lg"></i>
                                <span class="text-sm"><span class="font-semibold text-purple-600 dark:text-purple-400">Pilih File PDF</span> atau drag ke sini</span>
                            </div>
                        </div>
                    @endif
                    
                    <div wire:loading wire:target="file_pdf" class="text-purple-600 text-xs mt-2 font-medium flex items-center">
                        <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Memproses dokumen...
                    </div>
                    @error('file_pdf') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-800">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center mr-3">
                        <i class="fa-solid fa-boxes-packing"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">2. Rincian Barang / Jasa</h2>
                    </div>
                </div>
                
                <button type="button" wire:click="addItem" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 text-sm font-medium rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors border border-purple-200 dark:border-purple-800/50">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Baris
                </button>
            </div>

            <div class="hidden sm:grid sm:grid-cols-12 gap-4 mb-2 px-1">
                <div class="col-span-8 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nama Barang / Jasa</div>
                <div class="col-span-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Kuantitas</div>
                <div class="col-span-1 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide text-center">Aksi</div>
            </div>

            <div class="space-y-4 sm:space-y-3">
                @foreach ($items as $index => $item)
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-start sm:items-center bg-gray-50 dark:bg-gray-800/40 sm:bg-transparent sm:dark:bg-transparent p-4 sm:p-0 rounded-lg sm:rounded-none border border-gray-200 dark:border-gray-700 sm:border-none relative">
                        
                        <div class="sm:col-span-8 relative">
                            <label class="block sm:hidden text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nama Barang</label>
                            <div class="flex items-center">
                                <span class="hidden sm:flex text-sm text-gray-400 w-6 shrink-0">{{ $index + 1 }}.</span>
                                <input type="text" list="item-suggestions" wire:model="items.{{ $index }}.nama_item" class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 px-3.5 py-2 text-sm shadow-sm" placeholder="Ketik nama barang...">
                            </div>
                            @error('items.'.$index.'.nama_item') <span class="text-red-500 text-xs mt-1 block sm:ml-6">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-3 relative">
                            <label class="block sm:hidden text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Kuantitas (Unit)</label>
                            <div class="flex items-center">
                                <input type="number" min="1" wire:model="items.{{ $index }}.jumlah" class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 px-3.5 py-2 text-sm text-center shadow-sm" placeholder="0">
                                <span class="ml-3 text-sm text-gray-500 dark:text-gray-400 hidden sm:inline-block">Unit</span>
                            </div>
                            @error('items.'.$index.'.jumlah') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="sm:col-span-1 flex justify-end sm:justify-center">
                            @if(count($items) > 1)
                                <button type="button" wire:click="removeItem({{ $index }})" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Hapus Baris">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors focus:ring-4 focus:ring-purple-500/30" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="store">
                    <i class="fa-solid fa-paper-plane mr-2"></i> 
                    {{ $permohonan_id ? 'Simpan Perubahan' : 'Kirim Permohonan' }}
                </span>
                <span wire:loading wire:target="store">
                    <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Memproses...
                </span>
            </button>
        </div>
    </form>
</div>