<div class="w-full pb-12" wire:poll.10s>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="bg-white dark:bg-card-dark rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-800 mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between relative overflow-hidden group">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-purple-500/5 dark:bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 w-full">
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs font-bold rounded-full uppercase tracking-wider">
                    Unit Pengaju
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2 tracking-tight">
                {{ $unitName }}
            </h1>
            <p class="text-sm sm:text-base !text-gray-600 dark:text-gray-300 max-w-xl leading-relaxed mt-1">
    Selamat datang, <span class="font-semibold text-black dark:text-white">{{ auth()->user()->name }}</span>! Pantau statistik, status pengadaan, dan buat permohonan baru Anda di sini.
</p>
        </div>

        <div class="hidden sm:block relative z-10 mt-4 sm:mt-0 w-40 h-40 shrink-0 transition-transform hover:scale-110 duration-300">
            <lottie-player src="{{ asset('animasi/hello.json') }}" background="transparent" speed="1" style="width: 100%; height: 100%;" loop autoplay></lottie-player>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 group hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 group-hover:bg-gray-800 group-hover:text-white transition-all">
                    <i class="fa-solid fa-file-invoice text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pengajuan</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_permohonan'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 group hover:-translate-y-1.5 hover:shadow-xl hover:shadow-blue-500/10 hover:border-blue-200 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:bg-blue-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-inbox text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Baru Masuk</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['baru'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 group hover:-translate-y-1.5 hover:shadow-xl hover:shadow-orange-500/10 hover:border-orange-200 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600 dark:text-orange-400 group-hover:bg-orange-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-spinner fa-spin-pulse text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Sedang Diproses</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['sedang_diproses'] }}</h3>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 group hover:-translate-y-1.5 hover:shadow-xl hover:shadow-emerald-500/10 hover:border-emerald-200 transition-all duration-300">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                    <i class="fa-solid fa-check-double text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Selesai / Terealisasi</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['selesai'] }}</h3>
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="lg:col-span-2 bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 relative" wire:ignore>
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-lg">Frekuensi Pengajuan</h3>
                    <p class="text-xs text-gray-500">Statistik permohonan per bulan di tahun {{ date('Y') }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center dark:bg-purple-900/30 dark:text-purple-400">
                    <i class="fa-solid fa-chart-column"></i>
                </div>
            </div>
            
            <div id="chart-pengajuan" class="w-full h-[300px]"></div>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('chartData', () => ({
                        init() {
                            let options = {
                                series: [{
                                    name: 'Total Permohonan',
                                    data: @json($chartData)
                                }],
                                chart: {
                                    type: 'bar',
                                    height: 300,
                                    toolbar: { show: false },
                                    fontFamily: 'inherit'
                                },
                                plotOptions: {
                                    bar: {
                                        borderRadius: 6,
                                        columnWidth: '45%',
                                    }
                                },
                                colors: ['#9333ea'], // Warna Ungu
                                dataLabels: { enabled: false },
                                xaxis: {
                                    categories: @json($months),
                                    labels: {
                                        style: { colors: '#9ca3af', fontSize: '12px' }
                                    },
                                    axisBorder: { show: false },
                                    axisTicks: { show: false }
                                },
                                yaxis: {
                                    labels: {
                                        style: { colors: '#9ca3af' },
                                        formatter: function (val) { return val.toFixed(0); }
                                    }
                                },
                                grid: {
                                    borderColor: '#f3f4f6',
                                    strokeDashArray: 4,
                                    yaxis: { lines: { show: true } }
                                },
                                tooltip: {
                                    theme: 'light' // Otomatis menyesuaikan, bisa diatur lewat CSS mode dark nanti
                                }
                            };

                            let chart = new ApexCharts(document.querySelector("#chart-pengajuan"), options);
                            chart.render();
                        }
                    }))
                })
            </script>
            <div x-data="chartData()"></div>
        </div>

        <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-3xl shadow-lg border border-purple-500/50 p-8 flex flex-col items-center justify-center text-center relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="absolute -left-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700 delay-100"></div>

            <div class="relative z-10 w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mb-6 backdrop-blur-sm border border-white/20 shadow-inner group-hover:-translate-y-2 transition-transform duration-300">
                <i class="fa-solid fa-file-circle-plus text-4xl text-white"></i>
            </div>
            
            <h3 class="font-bold text-white text-xl mb-2 relative z-10">Butuh Barang / Jasa?</h3>
            <p class="text-sm text-purple-100 mb-8 relative z-10">Ajukan permohonan pengadaan baru untuk unit Anda secara digital dan pantau statusnya secara real-time.</p>
            
            <a href="{{ route('user.buat_permohonan') }}" wire:navigate class="relative z-10 w-full inline-flex items-center justify-center px-6 py-3.5 bg-white text-purple-700 text-sm font-bold rounded-xl transition-all shadow-lg hover:shadow-white/20 hover:scale-[1.02] active:scale-95 gap-2">
                <i class="fa-solid fa-plus"></i> Buat Permohonan Sekarang
            </a>
        </div>

    </div>

    <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/30">
            <h3 class="font-bold text-gray-900 dark:text-white text-lg flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-purple-500"></i> Riwayat Permohonan Terakhir
            </h3>
            <a href="{{ route('user.riwayat_status') }}" wire:navigate class="text-sm font-medium text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 hover:underline flex items-center gap-1">
                Lihat Semua <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-800/50 dark:text-gray-400 border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Tgl. Pengajuan</th>
                        <th class="px-6 py-4 font-semibold">Judul Permohonan</th>
                        <th class="px-6 py-4 font-semibold">Status Saat Ini</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($recentRequests as $req)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($req->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white truncate max-w-xs">
                                {{ $req->judul }}
                            </td>
                            <td class="px-6 py-4">
                                @if($req->status == 'Baru')
                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">Baru Masuk</span>
                                @elseif($req->status == 'Proses')
                                    <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-semibold dark:bg-orange-900/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Diproses</span>
                                @elseif($req->status == 'Selesai')
                                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800"><i class="fa-solid fa-check mr-1"></i> Selesai</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fa-solid fa-folder-open text-4xl mb-3"></i>
                                    <p class="text-sm">Belum ada riwayat permohonan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>