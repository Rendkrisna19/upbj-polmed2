<div class="w-full pb-12" wire:poll.15s>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 flex-1 flex flex-col sm:flex-row items-center justify-between relative overflow-hidden group w-full">
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-primary/5 dark:bg-primary/10 rounded-full blur-3xl pointer-events-none transition-transform group-hover:scale-110 duration-700"></div>

            <div class="relative z-10 text-center sm:text-left w-full sm:w-2/3">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2 tracking-tight">
                    Ringkasan Laporan, 
                    <span class="text-primary">{{ explode(' ', auth()->user()->name)[0] }}</span>! 
                </h1>
                <p class="text-sm sm:text-base !text-gray-600 dark:text-gray-300 leading-relaxed mt-1">
                    Pantau seluruh alur pengadaan dari semua unit kerja/jurusan dalam satu tampilan terpusat.
                </p>
            </div>

            <div class="hidden sm:block relative z-10 w-32 h-32 shrink-0 transition-transform hover:scale-110 duration-300">
                <lottie-player src="{{ asset('animasi/hello.json') }}" background="transparent" speed="1" style="width: 100%; height: 100%;" loop autoplay></lottie-player>
            </div>
        </div>

        <div class="flex items-center gap-3 bg-white dark:bg-card-dark p-2 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 shrink-0 relative">
            <div wire:loading wire:target="filterBulan, filterTahun" class="absolute -top-2 -right-2">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                </span>
            </div>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i class="fa-regular fa-calendar-days text-sm"></i>
                </div>
                <select wire:model.live="filterBulan" class="bg-gray-50 dark:bg-gray-900 border-none text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-primary block pl-9 p-2 w-32 cursor-pointer font-medium transition-colors">
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
            <div class="w-px h-6 bg-gray-200 dark:bg-gray-700"></div>
            <select wire:model.live="filterTahun" class="bg-transparent border-none text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-primary block p-2 cursor-pointer font-medium transition-colors">
                @foreach($availableYears as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 group hover:-translate-y-1.5 hover:shadow-xl hover:border-primary/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Permohonan</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1 transition-opacity" wire:loading.class="opacity-50" wire:target="filterBulan, filterTahun">{{ $stats['total_permohonan'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-xl text-gray-600 dark:text-gray-400 group-hover:bg-primary group-hover:text-white group-hover:rotate-6 transition-all duration-300">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 group hover:-translate-y-1.5 hover:shadow-xl hover:shadow-orange-500/10 hover:border-orange-400 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan Berjalan</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1 transition-opacity" wire:loading.class="opacity-50" wire:target="filterBulan, filterTahun">{{ $stats['proses_pekerjaan'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-xl text-orange-600 dark:text-orange-400 group-hover:bg-orange-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-clock-rotate-left group-hover:-rotate-45 transition-transform duration-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 group hover:-translate-y-1.5 hover:shadow-xl hover:shadow-emerald-500/10 hover:border-emerald-400 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Realisasi Selesai</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mt-1 transition-opacity" wire:loading.class="opacity-50" wire:target="filterBulan, filterTahun">{{ $stats['realisasi_selesai'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-xl text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                    <i class="fa-solid fa-circle-check group-hover:scale-110 transition-transform"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col justify-between group">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white mb-2 text-center sm:text-left">Efisiensi Pekerjaan</h3>
                <p class="text-xs text-gray-500 text-center sm:text-left mb-6">Rasio permohonan selesai bulan ini</p>
            </div>
            
            <div class="flex items-center justify-center py-4 relative">
                <div class="relative w-40 h-40 flex items-center justify-center transition-opacity duration-300" wire:loading.class="opacity-30" wire:target="filterBulan, filterTahun">
                    <svg class="w-full h-full transform -rotate-90">
                        <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="transparent" class="text-gray-100 dark:text-gray-800" />
                        
                        @php 
                            $circumference = 2 * pi() * 70; // 439.8
                            $offset = $circumference - ($circumference * $stats['persentase_selesai'] / 100); 
                        @endphp
                        <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="transparent" 
                                stroke-dasharray="{{ $circumference }}" 
                                stroke-dashoffset="{{ $offset }}" 
                                stroke-linecap="round"
                                class="text-primary transition-all duration-1000 ease-out drop-shadow-md" />
                    </svg>
                    <div class="absolute flex flex-col items-center justify-center">
                        <span class="text-3xl font-black text-gray-900 dark:text-white">{{ $stats['persentase_selesai'] }}%</span>
                        <span class="text-[10px] text-gray-400 font-bold tracking-widest uppercase">Selesai</span>
                    </div>
                </div>
            </div>
            
            <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-6 leading-relaxed bg-gray-50 dark:bg-gray-800/50 p-4 rounded-2xl">
                Dari total <span class="font-bold text-gray-900 dark:text-white">{{ $stats['total_permohonan'] }}</span> permohonan masuk bulan ini, <span class="font-bold text-primary">{{ $stats['persentase_selesai'] }}%</span> telah diselesaikan hingga tahap realisasi akhir.
            </p>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/30">
                <h3 class="font-bold text-gray-900 dark:text-white text-lg flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-primary"></i> Status Permohonan Terbaru
                </h3>
                <a href="{{ route('admin.permohonan_masuk') }}" wire:navigate class="text-sm font-medium text-primary hover:text-primary-dark hover:underline flex items-center gap-1 transition-colors">
                    Semua Laporan <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
            
            <div class="overflow-x-auto flex-1 relative">
                <div wire:loading.flex wire:target="filterBulan, filterTahun" class="absolute inset-0 z-10 bg-white/60 dark:bg-gray-900/60 backdrop-blur-sm items-center justify-center">
                    <i class="fa-solid fa-circle-notch fa-spin text-primary text-3xl"></i>
                </div>

                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Tgl / Unit Pengaju</th>
                            <th class="px-6 py-4 font-semibold">Judul Permohonan</th>
                            <th class="px-6 py-4 font-semibold text-right">Status Terakhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse ($recentPermohonan as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white mb-1">{{ $item->unit->nama_unit ?? 'Unit Tidak Diketahui' }}</div>
                                    <div class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider flex items-center">
                                        <i class="fa-regular fa-calendar mr-1.5 text-gray-400"></i> {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300 font-medium truncate max-w-[200px]" title="{{ $item->judul }}">
                                    {{ $item->judul }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($item->status == 'Baru')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-200 text-xs font-bold dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mr-1.5 animate-pulse"></span> Baru Masuk
                                        </span>
                                    @elseif($item->status == 'Proses')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-orange-50 text-orange-700 border border-orange-200 text-xs font-bold dark:bg-orange-900/30 dark:text-orange-400 dark:border-orange-800">
                                            <i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Pekerjaan
                                        </span>
                                    @elseif($item->status == 'Selesai')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800">
                                            <i class="fa-solid fa-check mr-1.5"></i> Realisasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-red-50 text-red-700 border border-red-200 text-xs font-bold dark:bg-red-900/30 dark:text-red-400 dark:border-red-800">
                                            <i class="fa-solid fa-ban mr-1.5"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-50"></i>
                                        <p class="text-sm font-medium">Tidak ada permohonan pada bulan ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>