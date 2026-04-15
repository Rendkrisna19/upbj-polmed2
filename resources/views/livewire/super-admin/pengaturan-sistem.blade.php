<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Sistem Global</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Konfigurasi integrasi notifikasi, API, dan parameter inti aplikasi.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col">
            <div class="bg-blue-50/50 dark:bg-blue-900/10 p-6 border-b border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Notifikasi Email</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pengaturan pengiriman SMTP Gmail</p>
                </div>
            </div>

            <form wire:submit="saveGroup('notifikasi_email')" class="p-6 sm:p-8 space-y-6 flex-1 flex flex-col justify-between">
                <div class="space-y-6">
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Status Fitur Email</p>
                            <p class="text-xs text-gray-500">Aktifkan untuk mengirim email ke unit dan admin otomatis.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" wire:model="state.is_email_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div x-show="$wire.state.is_email_active" x-collapse>
                        <div class="space-y-5 pt-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Pengirim (Sender)</label>
                                <input type="email" wire:model="state.email_sender_address" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Pengirim (Tampil di Inbox)</label>
                                <input type="text" wire:model="state.email_sender_name" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-blue-500 focus:border-blue-500 p-3 transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-gray-800 mt-6">
                    <button type="submit" class="w-full px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="saveGroup('notifikasi_email')"><i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan Email</span>
                        <span wire:loading wire:target="saveGroup('notifikasi_email')"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col">
            <div class="bg-emerald-50/50 dark:bg-emerald-900/10 p-6 border-b border-gray-100 dark:border-gray-800 flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl shadow-inner">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Notifikasi WhatsApp</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pengaturan Gateway API Fonnte</p>
                </div>
            </div>

            <form wire:submit="saveGroup('notifikasi_wa')" class="p-6 sm:p-8 space-y-6 flex-1 flex flex-col justify-between">
                <div class="space-y-6">
                    <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700">
                        <div>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Status Fitur WhatsApp</p>
                            <p class="text-xs text-gray-500">Kirim struk/status langsung ke nomor WhatsApp.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" wire:model="state.is_wa_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div x-show="$wire.state.is_wa_active" x-collapse>
                        <div class="space-y-5 pt-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 flex items-center justify-between">
                                    API Token (Fonnte)
                                    <a href="https://fonnte.com" target="_blank" class="text-xs text-emerald-600 hover:underline">Dapatkan Token</a>
                                </label>
                                <input type="password" wire:model="state.wa_api_token" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-3 transition-colors" placeholder="••••••••••••••••">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-gray-800 mt-6">
                    <button type="submit" class="w-full px-6 py-3 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="saveGroup('notifikasi_wa')"><i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan WA</span>
                        <span wire:loading wire:target="saveGroup('notifikasi_wa')"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>