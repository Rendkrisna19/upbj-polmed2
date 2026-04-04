<div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Master Unit</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data seluruh unit/jurusan yang ada di sistem.</p>
        </div>

        <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" class="bg-white dark:bg-card-dark border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-primary focus:border-primary block w-full pl-10 p-2.5 transition-colors" placeholder="Cari nama unit...">
            </div>

            <button wire:click="create" class="w-full sm:w-auto flex items-center justify-center px-4 py-2.5 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-xl shadow-sm shadow-primary/30 transition-all active:scale-95">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Unit
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 dark:text-gray-400 uppercase bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold w-16 text-center">No</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Nama Unit / Jurusan</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($units as $index => $unit)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                            <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                {{ $units->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $unit->nama_unit }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="edit({{ $unit->id }})" class="w-8 h-8 rounded-lg flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 transition-colors" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button wire:click="delete({{ $unit->id }})" wire:confirm="Apakah Anda yakin ingin menghapus unit '{{ $unit->nama_unit }}' ini? Data yang dihapus tidak dapat dikembalikan." class="w-8 h-8 rounded-lg flex items-center justify-center text-red-600 bg-red-50 hover:bg-red-100 dark:text-red-400 dark:bg-red-900/20 dark:hover:bg-red-900/40 transition-colors" title="Hapus">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                    <i class="fa-solid fa-folder-open text-4xl mb-3"></i>
                                    <p class="text-sm">Tidak ada data unit yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($units->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800">
                {{ $units->links() }}
            </div>
        @endif
    </div>

    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/50 dark:bg-black/60 backdrop-blur-sm transition-opacity" x-data x-transition.opacity>
            
            <div class="relative p-4 w-full max-w-md h-auto" @click.away="@this.closeModal()">
                <div class="relative bg-white dark:bg-card-dark rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 p-6 sm:p-8">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ $modalTitle }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors bg-gray-50 dark:bg-gray-800 p-2 rounded-xl">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="store">
                        <div class="mb-6">
                            <label for="nama_unit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Unit / Jurusan <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="nama_unit" id="nama_unit" class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-xl focus:ring-primary focus:border-primary block w-full p-3 transition-colors" placeholder="Contoh: Jurusan Teknik Komputer">
                            
                            @error('nama_unit') 
                                <span class="text-red-500 text-xs mt-1.5 flex items-center"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</span> 
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" wire:click="closeModal" class="px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-xl transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-xl shadow-sm shadow-primary/30 transition-all flex items-center justify-center min-w-[100px]">
                                <span wire:loading.remove wire:target="store">Simpan Data</span>
                                <span wire:loading wire:target="store"><i class="fa-solid fa-circle-notch fa-spin"></i></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>