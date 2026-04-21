<div>
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Sistem Global</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Konfigurasi identitas, integrasi notifikasi, dan parameter inti aplikasi.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        
        <div class="bg-white dark:bg-card-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col">
            <div class="bg-purple-50/50 dark:bg-purple-900/10 p-5 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg shadow-inner">
                    <i class="fa-solid fa-desktop"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Identitas Sistem</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Nama, Logo, dan Favicon</p>
                </div>
            </div>

            <form wire:submit="saveBrand" class="p-5 space-y-5 flex-1 flex flex-col justify-between">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Aplikasi / Instansi</label>
                        <input type="text" wire:model="state.app_name" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-purple-500 focus:border-purple-500 p-2.5 text-sm transition-colors" placeholder="Misal: UPBJ POLMED">
                        @error('state.app_name') <span class="text-red-500 text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Logo Utama (Sidebar/Login)</label>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-center overflow-hidden shrink-0">
                                @if ($new_app_logo)
                                    <img src="{{ $new_app_logo->temporaryUrl() }}" class="w-full h-full object-contain p-1">
                                @elseif (!empty($state['app_logo']))
                                    <img src="{{ asset('storage/' . $state['app_logo']) }}" class="w-full h-full object-contain p-1">
                                @else
                                    <i class="fa-solid fa-image text-gray-300 text-xl"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" wire:model="new_app_logo" accept="image/png, image/jpeg" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-colors cursor-pointer border border-gray-200 dark:border-gray-700 rounded-lg p-1">
                            </div>
                        </div>
                        <div wire:loading wire:target="new_app_logo" class="text-purple-600 text-[10px] mt-1 animate-pulse">Menyiapkan preview...</div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Favicon (Ikon Tab Browser)</label>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-center overflow-hidden shrink-0">
                                @if ($new_app_favicon)
                                    <img src="{{ $new_app_favicon->temporaryUrl() }}" class="w-full h-full object-contain p-2">
                                @elseif (!empty($state['app_favicon']))
                                    <img src="{{ asset('storage/' . $state['app_favicon']) }}" class="w-full h-full object-contain p-2">
                                @else
                                    <i class="fa-solid fa-globe text-gray-300 text-xl"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" wire:model="new_app_favicon" accept="image/png, image/x-icon" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition-colors cursor-pointer border border-gray-200 dark:border-gray-700 rounded-lg p-1">
                            </div>
                        </div>
                        <div wire:loading wire:target="new_app_favicon" class="text-purple-600 text-[10px] mt-1 animate-pulse">Menyiapkan preview...</div>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit" class="w-full px-4 py-2.5 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors shadow-sm flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="saveBrand"><i class="fa-solid fa-floppy-disk"></i> Simpan Identitas</span>
                        <span wire:loading wire:target="saveBrand"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col">
            <div class="bg-blue-50/50 dark:bg-blue-900/10 p-5 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shadow-inner">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Notifikasi Email</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Pengaturan SMTP Gmail</p>
                </div>
            </div>

            <form wire:submit="saveGroup('notifikasi_email')" class="p-5 space-y-5 flex-1 flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                        <div>
                            <p class="text-xs font-bold text-gray-900 dark:text-white">Status Fitur Email</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" wire:model="state.is_email_active" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div x-show="$wire.state.is_email_active" x-collapse>
                        <div class="space-y-4 pt-1">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Email Pengirim (Sender)</label>
                                <input type="email" wire:model="state.email_sender_address" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Pengirim</label>
                                <input type="text" wire:model="state.email_sender_name" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 text-sm transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit" class="w-full px-4 py-2.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="saveGroup('notifikasi_email')"><i class="fa-solid fa-floppy-disk"></i> Simpan Email</span>
                        <span wire:loading wire:target="saveGroup('notifikasi_email')"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col">
            <div class="bg-emerald-50/50 dark:bg-emerald-900/10 p-5 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg shadow-inner">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Notifikasi WhatsApp</h3>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400">Pengaturan Gateway Fonnte</p>
                </div>
            </div>

            <form wire:submit="saveGroup('notifikasi_wa')" class="p-5 space-y-5 flex-1 flex flex-col justify-between">
                <div class="space-y-4">
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                        <div>
                            <p class="text-xs font-bold text-gray-900 dark:text-white">Status Fitur WA</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" wire:model="state.is_wa_active" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div x-show="$wire.state.is_wa_active" x-collapse>
                        <div class="space-y-4 pt-1">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1 flex justify-between">
                                    API Token
                                    <a href="https://fonnte.com" target="_blank" class="text-[10px] text-emerald-600 hover:underline">Ambil Token</a>
                                </label>
                                <input type="password" wire:model="state.wa_api_token" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 p-2.5 text-sm transition-colors" placeholder="••••••••••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-800">
                    <button type="submit" class="w-full px-4 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition-colors shadow-sm flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="saveGroup('notifikasi_wa')"><i class="fa-solid fa-floppy-disk"></i> Simpan WhatsApp</span>
                        <span wire:loading wire:target="saveGroup('notifikasi_wa')"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>