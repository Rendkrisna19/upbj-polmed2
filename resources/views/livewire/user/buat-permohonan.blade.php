<div class="w-full pb-12">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                {{ $permohonan_id ? 'Edit Permohonan Pengadaan' : 'Form Permohonan Baru' }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                {{ $permohonan_id ? 'Perbarui informasi dan rincian dokumen pengajuan Anda di bawah ini.' : 'Lengkapi informasi di bawah ini beserta rincian daftar barang/jasa yang dibutuhkan unit Anda.' }}
            </p>
        </div>
        
        <a href="{{ route('user.riwayat_status') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Riwayat
        </a>
    </div>

    <datalist id="item-suggestions">
        @foreach($suggestions as $sug)
            <option value="{{ $sug }}">
        @endforeach
    </datalist>

    <form wire:submit.prevent="store" class="space-y-8">
        
        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 sm:p-8 shadow-md border-t-4 border-t-primary border-x border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center mr-4 shadow-inner">
                    <i class="fa-solid fa-file-signature text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">1. Informasi Surat Pengajuan</h2>
                    <p class="text-xs text-gray-500">Detail dasar mengenai tujuan pengadaan</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Judul Permohonan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="fa-solid fa-heading text-gray-400"></i>
                        </div>
                        <input type="text" wire:model="judul" class="w-full bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary pl-11 p-3.5 transition-all shadow-sm" placeholder="Misal: Pengadaan AC untuk Ruang Lab Komputer">
                    </div>
                    @error('judul') <span class="text-red-500 text-xs mt-1.5 block font-medium"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Surat <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="date" wire:model="tanggal" class="w-full bg-gray-50/50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary p-3.5 transition-all shadow-sm">
                    </div>
                    @error('tanggal') <span class="text-red-500 text-xs mt-1.5 block font-medium"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Dokumen Pendukung (PDF) 
                        {!! $permohonan_id ? '<span class="text-gray-400 font-normal text-xs ml-1">(Opsional, biarkan kosong jika tidak diubah)</span>' : '<span class="text-red-500">*</span>' !!}
                    </label>
                    
                    <div class="relative w-full bg-gray-50/50 dark:bg-gray-900/50 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl flex items-center justify-center p-2 hover:border-primary hover:bg-primary/5 transition-all focus-within:ring-2 focus-within:ring-primary/20 focus-within:border-solid focus-within:border-primary group">
                        
                        <input type="file" wire:model="file_pdf" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <div class="flex items-center space-x-3 pointer-events-none">
                            <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                            </div>
                            <div class="text-sm">
                                <span class="font-bold text-primary">Pilih File PDF</span>
                                <span class="text-gray-500 dark:text-gray-400 font-medium ml-1">atau drag ke sini</span>
                            </div>
                        </div>
                    </div>
                    
                    <div wire:loading wire:target="file_pdf" class="text-primary text-xs mt-2 font-medium flex items-center">
                        <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Sedang mengunggah dokumen...
                    </div>
                    @error('file_pdf') <span class="text-red-500 text-xs mt-1.5 block font-medium"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 sm:p-8 shadow-md border-t-4 border-t-blue-500 border-x border-b border-gray-100 dark:border-gray-800">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div class="flex items-center mb-4 sm:mb-0">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center mr-4 shadow-inner">
                        <i class="fa-solid fa-boxes-packing text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">2. Rincian Barang / Jasa</h2>
                        <p class="text-xs text-gray-500">Daftar kebutuhan yang diajukan</p>
                    </div>
                </div>
                
                <button type="button" wire:click="addItem" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-sm font-bold rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-colors border border-blue-200 dark:border-blue-800/50 shadow-sm">
                    <i class="fa-solid fa-plus mr-2"></i> Tambah Baris
                </button>
            </div>

            <div class="bg-gray-50/50 dark:bg-gray-800/30 rounded-2xl border border-gray-100 dark:border-gray-800 p-2 sm:p-4">
                <div class="hidden sm:grid sm:grid-cols-12 gap-4 mb-3 px-2">
                    <div class="col-span-8 text-xs font-bold text-gray-400 uppercase tracking-wider pl-10">Nama Barang / Jasa</div>
                    <div class="col-span-3 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Kuantitas</div>
                    <div class="col-span-1 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Aksi</div>
                </div>

                <div class="space-y-3">
                    @foreach ($items as $index => $item)
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-start sm:items-center bg-white dark:bg-gray-900 p-4 sm:p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 transition-all hover:border-blue-300 dark:hover:border-blue-700 shadow-sm relative group">
                            
                            <div class="sm:col-span-8 relative flex items-center">
                                <div class="hidden sm:flex w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 text-xs font-bold items-center justify-center mr-3 shrink-0 group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                    {{ $index + 1 }}
                                </div>
                                <div class="w-full">
                                    <label class="block sm:hidden text-xs font-medium text-gray-500 mb-1.5">Nama Barang #{{ $index + 1 }}</label>
                                    <input type="text" list="item-suggestions" wire:model="items.{{ $index }}.nama_item" class="w-full bg-transparent border-none text-gray-900 dark:text-gray-100 focus:ring-0 p-2 text-sm sm:text-base font-medium" placeholder="Ketik nama barang...">
                                </div>
                                @error('items.'.$index.'.nama_item') <span class="text-red-500 text-xs mt-1 absolute -bottom-5 left-10">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-3 relative mt-2 sm:mt-0">
                                <label class="block sm:hidden text-xs font-medium text-gray-500 mb-1.5">Jumlah</label>
                                <div class="relative flex items-center justify-center">
                                    <input type="number" min="1" wire:model="items.{{ $index }}.jumlah" class="w-24 sm:w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 p-2.5 text-center font-bold" placeholder="0">
                                    <span class="ml-2 text-xs font-bold text-gray-400 uppercase">Unit</span>
                                </div>
                                @error('items.'.$index.'.jumlah') <span class="text-red-500 text-xs mt-1 absolute -bottom-5 left-0">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-1 flex justify-end sm:justify-center items-center mt-3 sm:mt-0">
                                @if(count($items) > 1)
                                    <button type="button" wire:click="removeItem({{ $index }})" class="w-10 h-10 rounded-lg flex items-center justify-center text-red-400 hover:bg-red-500 hover:text-white dark:text-red-500 dark:hover:bg-red-600 dark:hover:text-white transition-all bg-red-50 dark:bg-red-900/10 border border-transparent hover:border-red-600" title="Hapus Baris">
                                        <i class="fa-solid fa-xmark text-lg"></i>
                                    </button>
                                @else
                                    <div class="w-10 h-10 hidden sm:block opacity-0"></div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-end pt-2 gap-4">
            <button type="submit" class="w-full sm:w-auto flex justify-center items-center px-10 py-4 bg-gradient-to-r from-primary to-primary-light hover:from-primary-dark hover:to-primary text-white text-sm font-bold rounded-xl shadow-lg shadow-primary/40 transition-all active:scale-[0.98]" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="store">
                    <i class="fa-solid fa-paper-plane mr-2"></i> 
                    {{ $permohonan_id ? 'Simpan Perubahan' : 'Kirim Permohonan Sekarang' }}
                </span>
                <span wire:loading wire:target="store">
                    <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Menyimpan Data...
                </span>
            </button>
        </div>

    </form>
</div>