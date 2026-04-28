<div>
    <div class="mb-8 rounded-3xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800 transition-all duration-300">
        
        <img src="{{ asset('banner/banner-putih.png') }}" 
             alt="Banner Terang" 
             class="w-full h-32 md:h-48 lg:h-56 object-cover block dark:hidden transition-opacity duration-300">
             
        <img src="{{ asset('banner/banner-hitam.png') }}" 
             alt="Banner Gelap" 
             class="w-full h-32 md:h-48 lg:h-56 object-cover hidden dark:block transition-opacity duration-300">
             
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        {{-- <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                {{ $this->greeting }}, {{ explode(' ', auth()->user()->name ?? 'Super Admin')[0] }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-2">
                <i class="fa-regular fa-clock"></i> {{ $this->currentDate }}
            </p>
        </div> --}}

        <div class="flex items-center gap-3 bg-white dark:bg-card-dark p-2 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 relative">
            
            <div wire:loading wire:target="filterBulan, filterTahun" class="absolute -top-2 -right-2">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-primary"></span>
                </span>
            </div>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i class="fa-regular fa-calendar-days"></i>
                </div>
                <select wire:model.live="filterBulan" class="bg-gray-50 dark:bg-gray-900 border-none text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-primary block pl-9 p-2.5 w-32 cursor-pointer font-medium transition-colors">
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

            <select wire:model.live="filterTahun" class="bg-transparent border-none text-gray-900 dark:text-gray-100 text-sm rounded-xl focus:ring-primary block p-2.5 cursor-pointer font-medium transition-colors">
                @foreach($availableYears as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl hover:shadow-blue-500/10 dark:hover:shadow-blue-500/10 hover:border-blue-200 dark:hover:border-blue-800/50">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Total Pengguna</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl shadow-inner transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="mt-4 relative z-10">
                <a href="{{ route('users.index') }}" wire:navigate class="text-xs font-semibold text-blue-600 hover:text-blue-800 dark:text-blue-400 flex items-center gap-1 transition-all group-hover:gap-2">
                    Kelola Pengguna <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl hover:shadow-purple-500/10 dark:hover:shadow-purple-500/10 hover:border-purple-200 dark:hover:border-purple-800/50">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-purple-50 dark:bg-purple-900/20 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Unit Terdaftar</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUnits) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl shadow-inner transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
            </div>
            <div class="mt-4 relative z-10">
                <a href="{{ route('unit.index') }}" wire:navigate class="text-xs font-semibold text-purple-600 hover:text-purple-800 dark:text-purple-400 flex items-center gap-1 transition-all group-hover:gap-2">
                    Master Unit <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl hover:shadow-primary/10 dark:hover:shadow-primary/10 hover:border-primary/30 dark:hover:border-primary/50">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-primary/10 dark:bg-primary/5 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">Siklus Bulan Ini</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white transition-opacity duration-200" 
                        wire:loading.class="opacity-50" 
                        wire:target="filterBulan, filterTahun">
                        {{ number_format($totalPermohonan) }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary/20 dark:bg-primary/20 text-primary dark:text-primary-light flex items-center justify-center text-xl shadow-inner transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                    <i class="fa-solid fa-file-contract"></i>
                </div>
            </div>
            <div class="mt-4 relative z-10">
                <p class="text-xs text-gray-500 dark:text-gray-400 transition-colors group-hover:text-primary/70 dark:group-hover:text-primary-light/70">
                    <span wire:loading.remove wire:target="filterBulan, filterTahun">Total data berdasarkan filter</span>
                    <span wire:loading wire:target="filterBulan, filterTahun" class="text-primary animate-pulse">Memuat data...</span>
                </p>
            </div>
        </div>

        <div class="bg-white dark:bg-card-dark rounded-3xl p-6 border border-gray-100 dark:border-gray-800 shadow-sm relative overflow-hidden group transition-all duration-300 ease-out hover:-translate-y-1.5 hover:shadow-xl hover:shadow-emerald-500/10 dark:hover:shadow-emerald-500/10 hover:border-emerald-200 dark:hover:border-emerald-800/50">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-full blur-2xl transition-transform duration-500 group-hover:scale-150"></div>
            <div class="relative z-10">
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-4">Sistem Notifikasi</p>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between group/item">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i class="fa-solid fa-envelope w-4 text-gray-400 group-hover/item:text-emerald-500 transition-colors"></i> Email SMTP
                        </span>
                        @if($isEmailActive)
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-md dark:bg-emerald-900/30 dark:text-emerald-400">AKTIF</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded-md dark:bg-gray-800 dark:text-gray-400">NONAKTIF</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between group/item">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp w-4 text-gray-400 group-hover/item:text-emerald-500 transition-colors"></i> WhatsApp API
                        </span>
                        @if($isWaActive)
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-md dark:bg-emerald-900/30 dark:text-emerald-400">AKTIF</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded-md dark:bg-gray-800 dark:text-gray-400">NONAKTIF</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-4 relative z-10">
                <a href="{{ route('pengaturan_sistem') }}" wire:navigate class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 flex items-center gap-1 transition-all group-hover:gap-2">
                    Konfigurasi <i class="fa-solid fa-gear transition-transform group-hover:rotate-90"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-card-dark rounded-3xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-user-clock text-primary"></i> Pengguna Baru Mendaftar
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50/50 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-4 font-semibold">User</th>
                        <th class="px-6 py-4 font-semibold">Unit</th>
                        <th class="px-6 py-4 font-semibold">Role</th>
                        <th class="px-6 py-4 font-semibold text-right">Terdaftar Pada</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($recentUsers as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-sm shadow-inner">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-200">
                            {{ $user->unit ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'super_admin')
                                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-1 rounded-md dark:bg-purple-900/30 dark:text-purple-400">Super Admin</span>
                            @elseif($user->role === 'admin')
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded-md dark:bg-blue-900/30 dark:text-blue-400">Admin UPBJ</span>
                            @else
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-1 rounded-md dark:bg-emerald-900/30 dark:text-emerald-400">User Unit</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-users-slash text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                <p>Belum ada pengguna terdaftar.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>