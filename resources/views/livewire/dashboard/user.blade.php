<div>
    <div class="bg-white dark:bg-card-dark rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-800 mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between relative overflow-hidden group">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-emerald-500/5 dark:bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 w-full">
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-full uppercase tracking-wider">
                    Unit / Divisi
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2 tracking-tight">
                {{ $unitName }}
            </h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 max-w-xl leading-relaxed mt-1">
                Selamat datang, <span class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</span>! Pantau status pengadaan dan buat permohonan baru di sini.
            </p>
        </div>

        <div class="hidden sm:flex relative z-10 mt-4 sm:mt-0 items-center justify-center w-16 h-16 bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-800 shadow-inner shrink-0">
            <i class="fa-solid fa-building text-2xl text-emerald-600 dark:text-emerald-400"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 group hover:border-primary/50 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 group-hover:bg-primary group-hover:text-white transition-all">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Permohonan</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_permohonan'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 group hover:border-orange-500/50 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 group-hover:bg-orange-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-spinner"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Sedang Diproses</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['sedang_diproses'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 group hover:border-emerald-500/50 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-check-double"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai / Terealisasi</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['selesai'] }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h3 class="font-bold text-gray-900 dark:text-white text-lg">Riwayat Permohonan Terakhir</h3>
                <a href="#" class="text-sm font-medium text-primary hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50/50 dark:bg-gray-800/30">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Judul Permohonan</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($recentRequests as $req)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($req['tanggal'])->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    {{ $req['judul'] }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full bg-{{ $req['color'] }}-100 text-{{ $req['color'] }}-700 text-xs font-semibold dark:bg-{{ $req['color'] }}-900/30 dark:text-{{ $req['color'] }}-400">
                                        {{ $req['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat permohonan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mb-4">
                <i class="fa-solid fa-file-signature text-3xl text-primary dark:text-primary-light"></i>
            </div>
            <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-2">Butuh Barang/Jasa?</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Buat permohonan pengadaan baru untuk unit Anda sekarang.</p>
            
            <a href="#" wire:navigate class="w-full inline-flex items-center justify-center px-4 py-3 bg-primary hover:bg-primary-dark text-white text-sm font-semibold rounded-xl transition-all shadow-sm shadow-primary/30 active:scale-95 gap-2">
                <i class="fa-solid fa-plus"></i> Buat Permohonan Baru
            </a>
        </div>

    </div>
</div>