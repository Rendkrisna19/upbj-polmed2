<div>
    <div class="bg-white dark:bg-card-dark rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-800 mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between relative overflow-hidden group">
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-primary/5 dark:bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2 tracking-tight">
                Ringkasan Laporan, 
                <span class="text-primary">{{ auth()->user()->name }}</span>! 📊
            </h1>
            <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 max-w-xl leading-relaxed mt-1">
                Pantau seluruh alur pengadaan dari semua unit/jurusan dalam satu tampilan terpusat.
            </p>
        </div>

        <div class="hidden sm:flex relative z-10 mt-4 sm:mt-0 items-center justify-center w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-800 shadow-inner">
            <i class="fa-solid fa-chart-pie text-2xl text-blue-600 dark:text-blue-400"></i>
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
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan Berjalan</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['proses_pekerjaan'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 group hover:border-emerald-500/50 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Realisasi Selesai</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['realisasi_selesai'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800">
            <h3 class="font-bold text-gray-900 dark:text-white mb-6">Efisiensi Pekerjaan</h3>
            <div class="flex items-center justify-center py-4">
                <div class="relative w-32 h-32 flex items-center justify-center">
                    <svg class="w-full h-full transform -rotate-90">
                        <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="10" fill="transparent" class="text-gray-100 dark:text-gray-800" />
                        <circle cx="64" cy="64" r="58" stroke="currentColor" stroke-width="10" fill="transparent" stroke-dasharray="364.4" stroke-dashoffset="{{ 364.4 - (364.4 * $stats['persentase_selesai'] / 100) }}" class="text-primary" />
                    </svg>
                    <span class="absolute text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['persentase_selesai'] }}%</span>
                </div>
            </div>
            <p class="text-center text-sm text-gray-500 mt-4 leading-relaxed">
                Dari total permohonan masuk, <span class="font-bold text-primary">{{ $stats['persentase_selesai'] }}%</span> telah diselesaikan hingga tahap realisasi.
            </p>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <h3 class="font-bold text-gray-900 dark:text-white text-lg">Status Permohonan Per Unit</h3>
                <button class="text-sm font-medium text-primary hover:underline">Semua Laporan</button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-gray-50/50 dark:bg-gray-800/30">
                        <tr>
                            <th class="px-6 py-4">Nama Unit</th>
                            <th class="px-6 py-4">Permohonan</th>
                            <th class="px-6 py-4">Status Terakhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">Jurusan Teknik Komputer</td>
                            <td class="px-6 py-4">Pengadaan Server Lab</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold dark:bg-orange-900/30 dark:text-orange-400">Pekerjaan</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">Bagian Administrasi Umum</td>
                            <td class="px-6 py-4">ATK Semester Ganjil</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold dark:bg-emerald-900/30 dark:text-emerald-400">Realisasi</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>