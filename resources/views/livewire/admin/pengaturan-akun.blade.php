<div>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Akun</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola informasi profil dan keamanan akun Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">Informasi Profil</h3>
                
                <form wire:submit="updateProfile" class="space-y-6">
                    
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
                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">Foto Profil</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format JPG, JPEG, atau PNG. Maksimal 2MB.</p>
                            <div wire:loading wire:target="new_photo" class="text-primary text-xs font-medium mt-2 animate-pulse">
                                Mengunggah preview...
                            </div>
                            @error('new_photo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors">
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat Email</label>
                            <input type="email" wire:model="email" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors">
                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor WhatsApp <span class="text-red-500">*</span></label>
                            <div class="flex">
                                <select wire:model="country_code" class="bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm rounded-l-xl focus:ring-primary focus:border-primary p-3 border-r-0 transition-colors">
                                    <option value="62">🇮🇩 +62</option>
                                    <option value="1">🇺🇸 +1</option>
                                    <option value="44">🇬🇧 +44</option>
                                    <option value="60">🇲🇾 +60</option>
                                    <option value="65">🇸🇬 +65</option>
                                </select>
                                <input type="text" wire:model="no_hp" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-r-xl focus:ring-primary focus:border-primary p-3 transition-colors" placeholder="812345678xx">
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1"><i class="fa-solid fa-circle-info mr-1"></i>Masukkan nomor tanpa angka 0 di depan.</p>
                            @error('no_hp') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        @if(auth()->user()->role === 'user')
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unit / Divisi</label>
                            <input type="text" wire:model="unit" disabled class="w-full bg-gray-100 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 rounded-xl p-3 cursor-not-allowed opacity-80">
                            <p class="text-[11px] text-gray-400 mt-1.5"><i class="fa-solid fa-circle-info mr-1"></i>Perubahan unit hanya dapat dilakukan oleh Super Admin.</p>
                        </div>
                        @endif
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-primary hover:bg-primary-dark rounded-xl transition-colors shadow-sm flex items-center gap-2">
                            <span wire:loading.remove wire:target="updateProfile"><i class="fa-solid fa-floppy-disk"></i> Simpan Profil</span>
                            <span wire:loading wire:target="updateProfile"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">Keamanan</h3>
                
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" wire:model="new_password_confirmation" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-primary focus:border-primary p-3 transition-colors" placeholder="••••••••">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full px-6 py-3 text-sm font-medium text-gray-700 dark:text-white bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-xl transition-colors shadow-sm flex justify-center items-center gap-2">
                            <span wire:loading.remove wire:target="updatePassword"><i class="fa-solid fa-key"></i> Perbarui Sandi</span>
                            <span wire:loading wire:target="updatePassword"><i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>