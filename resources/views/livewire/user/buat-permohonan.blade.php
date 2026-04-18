<div class="w-full pb-12 relative">
    
    <div wire:loading.flex wire:target="store" class="fixed inset-0 z-[100] bg-gray-900/40 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-2xl flex flex-col items-center max-w-sm w-full mx-4 animate-pulse">
            <div class="w-16 h-16 border-4 border-purple-200 border-t-purple-600 rounded-full animate-spin mb-4"></div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Memproses Data</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Mohon tunggu sebentar, sedang menyimpan dokumen dan mengirim notifikasi...</p>
        </div>
    </div>

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

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Surat <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="tanggal" class="w-full sm:w-1/2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 py-2.5 px-3.5 text-sm transition-colors shadow-sm">
                    @error('tanggal') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-800">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center mr-3">
                        <i class="fa-regular fa-folder-open"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">2. Dokumen Lampiran (PDF)</h2>
                    </div>
                </div>
                
                <button type="button" wire:click="addDokumen" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-purple-50 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300 text-sm font-medium rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors border border-purple-200 dark:border-purple-800/50">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Dokumen Lain
                </button>
            </div>

            <div class="space-y-4">
                @foreach($dokumens as $index => $dokumen)
                    <div class="bg-gray-50 dark:bg-gray-800/40 border border-gray-200 dark:border-gray-700 rounded-xl p-5 relative">
                        <button type="button" wire:click="removeDokumen({{ $index }})" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start pr-8">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File Dokumen {{ $index + 1 }}</label>
                                
                                @if($dokumen['file'] || $dokumen['existing_path'])
                                    <div class="flex items-center justify-between p-3 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
                                        <div class="flex items-center overflow-hidden">
                                            <i class="fa-solid fa-file-pdf text-red-500 text-xl mr-3 shrink-0"></i>
                                            <div class="truncate">
                                                <p class="text-sm font-semibold text-purple-900 dark:text-purple-100 truncate">
                                                    {{ $dokumen['file'] ? $dokumen['file']->getClientOriginalName() : 'Dokumen Tersimpan' }}
                                                </p>
                                                @if($dokumen['existing_path'] && !$dokumen['file'])
                                                    <a href="{{ Storage::url($dokumen['existing_path']) }}" target="_blank" class="text-[11px] text-purple-600 hover:underline">Lihat File Lama</a>
                                                @endif
                                            </div>
                                        </div>
                                        <label class="cursor-pointer shrink-0 ml-2 text-xs font-medium text-purple-600 hover:text-purple-800">
                                            <input type="file" wire:model="dokumens.{{ $index }}.file" accept="application/pdf" class="hidden">
                                            Ganti File
                                        </label>
                                    </div>
                                @else
                                    <div class="relative w-full bg-white dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg flex items-center justify-center p-3 hover:bg-purple-50 dark:hover:bg-purple-900/10 hover:border-purple-400 transition-colors cursor-pointer">
                                        <input type="file" wire:model="dokumens.{{ $index }}.file" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="flex items-center space-x-2 pointer-events-none text-gray-500 dark:text-gray-400">
                                            <i class="fa-solid fa-cloud-arrow-up text-purple-600 dark:text-purple-400 text-lg"></i>
                                            <span class="text-sm">Pilih File PDF</span>
                                        </div>
                                    </div>
                                @endif

                                <div wire:loading wire:target="dokumens.{{ $index }}.file" class="text-purple-600 text-xs mt-2 font-medium flex items-center">
                                    <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Mengunggah file, mohon tunggu...
                                </div>
                                @error("dokumens.$index.file") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Dokumen <span class="text-gray-400 font-normal text-xs">(Opsional)</span></label>
                                <input type="text" wire:model="dokumens.{{ $index }}.nama_dokumen" class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 px-3.5 py-2.5 text-sm shadow-sm" placeholder="Misal: Spesifikasi Teknis Komputer" {{ !($dokumen['file'] || $dokumen['existing_path']) ? 'disabled' : '' }}>
                                @if(!($dokumen['file'] || $dokumen['existing_path']))
                                    <p class="text-[11px] text-gray-400 mt-1"><i class="fa-solid fa-info-circle"></i> Upload file terlebih dahulu untuk memberi nama.</p>
                                @endif
                                @error("dokumens.$index.nama_dokumen") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-800">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center mr-3">
                        <i class="fa-solid fa-boxes-packing"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-900 dark:text-white">3. Rincian Barang / Jasa</h2>
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
                <span>
                    <i class="fa-solid fa-paper-plane mr-2"></i> 
                    {{ $permohonan_id ? 'Simpan Perubahan' : 'Kirim Permohonan' }}
                </span>
            </button>
        </div>
    </form>
</div>