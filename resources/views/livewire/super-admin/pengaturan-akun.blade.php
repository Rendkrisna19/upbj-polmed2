<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Akun</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola akses root (Super Admin) Anda di sini.</p>
        </div>
        <div class="hidden sm:block">
            <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1.5 rounded-lg dark:bg-purple-900/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800/50">
                <i class="fa-solid fa-crown mr-1"></i> Hak Akses Tertinggi
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-800 pb-4 relative z-10">Informasi Dasar</h3>
                
                <form wire:submit="updateProfile" class="space-y-6 relative z-10">
                    
                    <div class="flex flex-col sm:flex-row items-center gap-6 mb-8">
                        <div class="relative group">
                            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden border-4 border-gray-50 dark:border-gray-800 shadow-md bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                @if ($new_photo)
                                    <img src="{{ $new_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif ($current_photo)
                                    <img src="{{ asset('storage/' . $current_photo) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl sm:text-4xl font-bold text-primary">{{ substr($name, 0, 1) }}</span>
                                @endif
                            </div>

                            <label class="absolute bottom-0 right-0 w-8 h-8 sm:w-10 sm:h-10 bg-primary hover:bg-primary-dark text-white rounded-full flex items-center justify-center cursor-pointer shadow-lg transition-transform hover:scale-110 border-2 border-white dark:border-card-dark">
                                <i class="fa-solid fa-camera text-sm sm:text-base"></i>
                                <input type="file" wire:model="new_photo" class="hidden" accept="image/png, image/jpeg, image/jpg">
                            </label>
                        </div>
                        
                        <div class="text-center sm:text-left">
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">Avatar Administrator</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format JPG, JPEG, atau PNG. Maksimal 2MB.</p>
                            <div wire:loading wire:target="new_photo" class="text-primary text-xs font-medium mt-2 animate-pulse">
                                Memuat preview gambar...
                            </div>
                            @error('new_photo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors">
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Email Super Admin</label>
                            <input type="email" wire:model="email" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors">
                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-xl transition-colors shadow-sm flex items-center gap-2">
                            <span wire:loading.remove wire:target="updateProfile"><i class="fa-solid fa-floppy-disk"></i> Perbarui Data</span>
                            <span wire:loading wire:target="updateProfile"><i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">Keamanan Akun</h3>
                
                <form wire:submit="updatePassword" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kata Sandi Saat Ini</label>
                        <input type="password" wire:model="current_password" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors" placeholder="••••••••">
                        @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kata Sandi Baru</label>
                        <input type="password" wire:model="new_password" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors" placeholder="Min. 8 karakter">
                        @error('new_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Sandi Baru</label>
                        <input type="password" wire:model="new_password_confirmation" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors" placeholder="••••••••">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full px-6 py-3 text-sm font-medium text-gray-700 dark:text-white bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-xl transition-colors shadow-sm flex justify-center items-center gap-2">
                            <span wire:loading.remove wire:target="updatePassword"><i class="fa-solid fa-key"></i> Ganti Sandi</span>
                            <span wire:loading wire:target="updatePassword"><i class="fa-solid fa-circle-notch fa-spin"></i> Memvalidasi...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>