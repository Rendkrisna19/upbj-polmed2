<aside class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white dark:bg-card-dark border-r border-gray-100 dark:border-gray-800 transition-all duration-300 lg:static"
       :class="[
           sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
           sidebarCollapsed ? 'lg:w-20 w-64' : 'w-64'
       ]">
    
    <div class="h-16 flex items-center px-6 border-b border-gray-50 dark:border-gray-800/50 shrink-0 transition-all duration-300">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo POLMED" class="w-9 h-9 object-contain shrink-0 drop-shadow-sm">
            
            <span x-show="!sidebarCollapsed" class="text-xl font-extrabold text-gray-900 dark:text-white tracking-wide" x-transition>UPBJ <span class="text-primary">POLMED</span></span>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 space-y-8 overflow-x-hidden scrollbar-hide px-4">
        
        @php
            $dashboardRoute = match(auth()->user()->role ?? '') {
                'super_admin' => 'super_admin.dashboard',
                'admin' => 'admin.dashboard',
                'user' => 'user.dashboard',
                default => 'login'
            };
        @endphp

        <div>
            <p x-show="!sidebarCollapsed" class="px-2 mb-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Overview</p>
            <div class="space-y-1.5">
                <a href="{{ route($dashboardRoute) }}" wire:navigate class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('*.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100' }}">
                    <i class="fa-solid fa-house w-6 text-center text-lg {{ request()->routeIs('*.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-primary transition-colors' }}"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3">Dashboard</span>
                </a>
            </div>
        </div>

        @if(auth()->check() && auth()->user()->role === 'user')
        <div>
            <p x-show="!sidebarCollapsed" class="px-2 mb-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pengajuan</p>
            <div class="space-y-1.5">
                <a href="{{ route('user.buat_permohonan') }}" wire:navigate class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('user.buat_permohonan') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100' }}">
    <i class="fa-solid fa-file-circle-plus w-6 text-center text-lg {{ request()->routeIs('user.buat_permohonan') ? 'text-white' : 'text-gray-400 group-hover:text-primary transition-colors' }}"></i>
    <span x-show="!sidebarCollapsed" class="ml-3">Buat Permohonan</span>
</a>
               <a href="{{ route('user.riwayat_status') }}" wire:navigate class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('user.riwayat_status') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100' }}">
    <i class="fa-solid fa-clock-rotate-left w-6 text-center text-lg {{ request()->routeIs('user.riwayat_status') ? 'text-white' : 'text-gray-400 group-hover:text-primary transition-colors' }}"></i>
    <span x-show="!sidebarCollapsed" class="ml-3">Riwayat & Status</span>
</a>
            </div>
        </div>
        @endif

        @if(auth()->check() && auth()->user()->role === 'admin')
        <div>
            <p x-show="!sidebarCollapsed" class="px-2 mb-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Manajemen Pengadaan</p>
            <div class="space-y-1.5">
              <a href="{{ route('admin.permohonan_masuk') }}" wire:navigate class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.permohonan_masuk') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100' }}">
    <i class="fa-solid fa-inbox w-6 text-center text-lg {{ request()->routeIs('admin.permohonan_masuk') ? 'text-white' : 'text-gray-400 group-hover:text-primary transition-colors' }}"></i>
    <span x-show="!sidebarCollapsed" class="ml-3 flex-1">Permohonan Masuk</span>
    
    @php
        $pendingCount = \App\Models\Permohonan::where('status', 'Baru')->count();
    @endphp
    
    @if($pendingCount > 0)
        <span x-show="!sidebarCollapsed" class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
            {{ $pendingCount }}
        </span>
    @endif
</a>
               <a href="{{ route('admin.pekerjaan_berjalan') }}" wire:navigate class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.pekerjaan_berjalan') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100' }}">
    <i class="fa-solid fa-person-digging w-6 text-center text-lg {{ request()->routeIs('admin.pekerjaan_berjalan') ? 'text-white' : 'text-gray-400 group-hover:text-primary transition-colors' }}"></i>
    <span x-show="!sidebarCollapsed" class="ml-3">Pekerjaan Berjalan</span>
    
    @php
        $pekerjaanCount = \App\Models\Permohonan::where('status', 'Proses')->count();
    @endphp
    @if($pekerjaanCount > 0)
        <span x-show="!sidebarCollapsed" class="ml-auto bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 text-[10px] font-bold px-2 py-0.5 rounded-full">
            {{ $pekerjaanCount }}
        </span>
    @endif
</a>
                <a href="#" wire:navigate class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100">
                    <i class="fa-solid fa-check-double w-6 text-center text-lg text-gray-400 group-hover:text-primary transition-colors"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3">Realisasi Selesai</span>
                </a>
            </div>
        </div>
        
        <div>
            <p x-show="!sidebarCollapsed" class="px-2 mb-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Laporan</p>
            <div class="space-y-1.5">
                <a href="#" wire:navigate class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100">
                    <i class="fa-solid fa-file-pdf w-6 text-center text-lg text-gray-400 group-hover:text-primary transition-colors"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3">Semua Laporan</span>
                </a>
            </div>
        </div>
        @endif

        @if(auth()->check() && auth()->user()->role === 'super_admin')
        <div>
            <p x-show="!sidebarCollapsed" class="px-2 mb-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Data Master</p>
            <div class="space-y-1.5">
                <a href="{{ route('users.index') }}" wire:navigate class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('users.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100' }}">
                    <i class="fa-solid fa-users-gear w-6 text-center text-lg {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-400 group-hover:text-primary transition-colors' }}"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3">Kelola Pengguna</span>
                </a>
                
                <a href="{{ route('unit.index') }}" wire:navigate class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('unit.*') ? 'bg-primary text-white shadow-md shadow-primary/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100' }}">
                    <i class="fa-solid fa-sitemap w-6 text-center text-lg {{ request()->routeIs('unit.*') ? 'text-white' : 'text-gray-400 group-hover:text-primary transition-colors' }}"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3">Master Unit</span>
                </a>
            </div>
        </div>
        @endif

        <div>
            <p x-show="!sidebarCollapsed" class="px-2 mb-3 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pengaturan</p>
            <div class="space-y-1.5">
                <a href="#" wire:navigate class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100">
                    <i class="fa-solid fa-user-shield w-6 text-center text-lg text-gray-400 group-hover:text-primary transition-colors"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3">Pengaturan Akun</span>
                </a>

                @if(auth()->check() && auth()->user()->role === 'super_admin')
                <a href="#" wire:navigate class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-gray-900 dark:hover:text-gray-100">
                    <i class="fa-solid fa-sliders w-6 text-center text-lg text-gray-400 group-hover:text-primary transition-colors"></i>
                    <span x-show="!sidebarCollapsed" class="ml-3">Pengaturan Sistem</span>
                </a>
                @endif
            </div>
        </div>

    </nav>

    <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-card-dark/50" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
        <div class="relative">
            <button @click="profileOpen = !profileOpen" class="w-full flex items-center justify-between p-2 rounded-xl hover:bg-white dark:hover:bg-gray-800 transition-colors shadow-sm border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-primary/20 dark:bg-primary/30 text-primary dark:text-primary-light flex items-center justify-center font-bold shadow-inner">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div x-show="!sidebarCollapsed" class="text-left text-sm" x-transition>
                        <p class="font-semibold text-gray-900 dark:text-white truncate w-24">{{ auth()->user()->name ?? 'Alex Smith' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', auth()->user()->role ?? 'Admin') }}</p>
                    </div>
                </div>
                <i x-show="!sidebarCollapsed" class="fa-solid fa-ellipsis-vertical text-gray-400 px-2"></i>
            </button>

            <div x-show="profileOpen" 
                 x-transition.origin.bottom.left
                 class="absolute bottom-full left-0 mb-3 w-full bg-white dark:bg-card-dark rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50" x-cloak>
                
                <div class="px-4 py-3 border-b border-gray-50 dark:border-gray-800">
                    <p class="text-sm text-gray-900 dark:text-white font-medium">Sesi Aktif</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? 'email@polmed.ac.id' }}</p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition flex items-center">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-3"></i> Keluar Sistem
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>