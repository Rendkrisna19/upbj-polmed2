<div>
    <div class="bg-white dark:bg-gray-800/90 backdrop-blur-xl rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700 mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between relative overflow-hidden group">
        
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-purple-500/10 dark:bg-purple-500/20 rounded-full blur-3xl pointer-events-none transition-transform duration-700 group-hover:scale-110"></div>
        <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-blue-500/10 dark:bg-blue-500/20 rounded-full blur-3xl pointer-events-none transition-transform duration-700 group-hover:scale-110"></div>

        <div class="relative z-10">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2 tracking-tight">
                Selamat Datang, 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-500 dark:from-purple-400 dark:to-indigo-300">
                    {{ auth()->user()->name ?? 'Super Admin' }}
                </span>! 👋
            </h1>
            <p class="text-sm sm:text-base text-gray-900 dark:text-gray-300 max-w-xl leading-relaxed mt-1">
                Anda masuk sebagai <span class="font-bold text-purple-600 dark:text-purple-400">Super Admin</span>. Pantau dan kelola seluruh pengguna serta unit sistem dengan mudah hari ini.
            </p>
        </div>

        <div class="hidden sm:flex relative z-10 mt-4 sm:mt-0 items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-2xl border border-purple-100 dark:border-gray-600 shadow-inner group-hover:rotate-6 transition-transform duration-300">
            <i class="fa-solid fa-shield-halved text-2xl text-purple-600 dark:text-purple-400"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <span class="flex items-center text-xs font-semibold text-green-600 bg-green-50 dark:bg-green-900/30 dark:text-green-400 px-3 py-1.5 rounded-full shadow-sm">
                    <i class="fa-solid fa-arrow-trend-up mr-1.5"></i> +12%
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Pengguna</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">45</h3>
                    <span class="text-sm text-gray-400 font-medium">Akun</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-900/30 flex items-center justify-center text-purple-600 dark:text-purple-400 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                    <i class="fa-solid fa-sitemap text-xl"></i>
                </div>
                <span class="flex items-center text-xs font-semibold text-gray-600 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-full shadow-sm">
                    Tetap
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Unit Terdaftar</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">12</h3>
                    <span class="text-sm text-gray-400 font-medium">Divisi</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
            <div class="absolute right-0 bottom-0 opacity-20 pointer-events-none">
                <i class="fa-solid fa-server text-8xl -mr-4 -mb-4 text-white"></i>
            </div>
            <div class="flex justify-between items-start mb-6 relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white group-hover:scale-110 group-hover:-rotate-3 transition-all duration-300 border border-white/20">
                    <i class="fa-solid fa-server text-xl"></i>
                </div>
                <div class="flex items-center gap-2 bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/20">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-100 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                    </span>
                    <span class="text-xs font-bold text-white">Normal</span>
                </div>
            </div>
            <div class="relative z-10">
                <p class="text-sm font-medium text-emerald-50 mb-1">Status Server</p>
                <h3 class="text-3xl font-bold text-white tracking-tight">Online</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Aktivitas Sistem</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Statistik login pengguna 7 hari terakhir</p>
                </div>
                <button class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 rounded-xl transition-colors">
                    Minggu Ini <i class="fa-solid fa-chevron-down ml-1 text-xs"></i>
                </button>
            </div>
            <div id="areaChart" class="w-full h-[300px]"></div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Distribusi Unit</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Persentase pengguna per divisi</p>
            
            <div id="donutChart" class="flex justify-center h-[280px]"></div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Cek apakah dark mode aktif (opsional, untuk menyesuaikan warna teks chart)
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#9ca3af' : '#6b7280';

        // 1. KONFIGURASI AREA CHART (Aktivitas Sistem)
        var areaOptions = {
            series: [{
                name: 'Pengguna Aktif',
                data: [31, 40, 28, 51, 42, 109, 100]
            }],
            chart: {
                height: 300,
                type: 'area',
                fontFamily: 'inherit',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            colors: ['#8b5cf6'], // Warna Purple Tailwind
            dataLabels: { enabled: false },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: textColor }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: textColor }
                }
            },
            grid: {
                borderColor: isDarkMode ? '#374151' : '#f3f4f6',
                strokeDashArray: 4, // Garis putus-putus biar elegan
                yaxis: { lines: { show: true } },
                xaxis: { lines: { show: false } },
            },
            tooltip: { theme: isDarkMode ? 'dark' : 'light' }
        };

        var areaChart = new ApexCharts(document.querySelector("#areaChart"), areaOptions);
        areaChart.render();

        // 2. KONFIGURASI DONUT CHART (Distribusi Unit)
        var donutOptions = {
            series: [44, 55, 13, 33],
            chart: {
                type: 'donut',
                height: 300,
                fontFamily: 'inherit',
            },
            labels: ['IT Support', 'Keuangan', 'HRD', 'Operasional'],
            colors: ['#8b5cf6', '#3b82f6', '#10b981', '#f59e0b'], // Purple, Blue, Emerald, Amber
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: { show: true },
                            value: {
                                show: true,
                                color: isDarkMode ? '#f9fafb' : '#111827',
                                fontSize: '24px',
                                fontWeight: 700,
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Total',
                                color: textColor
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            stroke: {
                width: 0 // Hilangkan garis border antar potongan kue
            },
            legend: {
                position: 'bottom',
                labels: { colors: textColor }
            },
            tooltip: { theme: isDarkMode ? 'dark' : 'light' }
        };

        var donutChart = new ApexCharts(document.querySelector("#donutChart"), donutOptions);
        donutChart.render();
    });
</script>