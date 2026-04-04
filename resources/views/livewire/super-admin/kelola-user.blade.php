<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Pengguna</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manajemen akses dan hak pengguna aplikasi UPBJ.</p>
        </div>
        
        <button wire:click="create" class="inline-flex items-center justify-center px-4 py-2.5 bg-primary hover:bg-primary-dark text-white text-sm font-semibold rounded-xl transition-colors shadow-sm gap-2">
            <i class="fa-solid fa-user-plus"></i> Tambah Pengguna
        </button>
    </div>

    <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        
        <div class="p-6 border-b border-gray-100 dark:border-gray-800">
            <div class="relative max-w-md">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                </div>
                <input wire:model.live.debounce.500ms="search" type="search" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-primary focus:border-primary block pl-10 p-3 transition-colors" placeholder="Cari nama, email, atau unit...">
                
                <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 flex items-center pr-4">
                    <i class="fa-solid fa-circle-notch fa-spin text-primary"></i>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/50 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">Nama Pengguna</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Peran (Role)</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Unit / Divisi</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold shadow-inner uppercase">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->role === 'super_admin')
                                    <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-1 rounded-md dark:bg-purple-900/30 dark:text-purple-400">Super Admin</span>
                                @elseif($user->role === 'admin')
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-1 rounded-md dark:bg-blue-900/30 dark:text-blue-400">Admin</span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-1 rounded-md dark:bg-emerald-900/30 dark:text-emerald-400">User Unit</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->unit ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="edit({{ $user->id }})" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 p-2 transition-colors" title="Edit Data">
                                    <i class="fa-solid fa-pen-to-square text-lg"></i>
                                </button>
                                
                                @if(auth()->user()->id !== $user->id)
                                    <button type="button" @click="
                                        Swal.fire({
                                            title: 'Hapus Pengguna?',
                                            text: 'Apakah Anda yakin ingin menghapus pengguna ini secara permanen?',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#ef4444',
                                            cancelButtonColor: '#6b7280',
                                            confirmButtonText: 'Ya, Hapus!',
                                            cancelButtonText: 'Batal',
                                            background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                                            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#111827',
                                            customClass: {
                                                popup: 'rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800'
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                $wire.delete({{ $user->id }})
                                            }
                                        })
                                    " class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 p-2 transition-colors ml-2" title="Hapus Data">
                                        <i class="fa-solid fa-trash-can text-lg"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                    <i class="fa-solid fa-user-xmark text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Pengguna tidak ditemukan</h3>
                                <p class="text-sm text-gray-500">Kami tidak dapat menemukan apa yang Anda cari.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-100 dark:border-gray-800">
            {{ $users->links() }}
        </div>
    </div>

    @if($isModalOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-gray-900/60 backdrop-blur-sm p-4 transition-all">
            <div class="relative w-full max-w-lg bg-white dark:bg-card-dark rounded-3xl shadow-2xl p-6 sm:p-8 transform transition-all">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $isEditMode ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-gray-800 dark:hover:text-white rounded-lg p-2 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form wire:submit.prevent="store" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors" placeholder="Masukkan nama lengkap">
                        @error('name') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Email</label>
                        <input type="email" wire:model="email" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors" placeholder="contoh@polmed.ac.id">
                        @error('email') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Peran (Role)</label>
                            <select wire:model.live="role" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors">
                                <option value="user">User Unit</option>
                                <option value="admin">Admin UPBJ</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                            @error('role') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unit Divisi</label>
                            <select wire:model="unit" 
                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                    {{ in_array($role, ['super_admin', 'admin']) ? 'disabled' : '' }}>
                                <option value="">-- Pilih Unit --</option>
                                @foreach($unitsList as $u)
                                    <option value="{{ $u->nama_unit }}">{{ $u->nama_unit }}</option>
                                @endforeach
                            </select>
                            @error('unit') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            
                            @if(in_array($role, ['super_admin', 'admin']))
                                <span class="text-xs text-gray-500 mt-1 block">Role ini tidak perlu unit.</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kata Sandi {{ $isEditMode ? '(Opsional)' : '' }}</label>
                        <input type="password" wire:model="password" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors" placeholder="{{ $isEditMode ? 'Kosongkan jika tidak ingin mengubah sandi' : 'Minimal 8 karakter' }}">
                        @error('password') <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800 mt-6">
                        <button type="button" wire:click="closeModal" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-xl transition-colors shadow-sm flex items-center gap-2">
                            <span wire:loading.remove wire:target="store"><i class="fa-solid fa-floppy-disk"></i> Simpan Data</span>
                            <span wire:loading wire:target="store"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>