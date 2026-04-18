<header 
    x-data="headerWidget()" 
    x-init="initWidget()"
    class="bg-white/80 dark:bg-card-dark/80 backdrop-blur-lg border-b border-gray-100 dark:border-gray-800 h-20 flex items-center justify-between px-4 lg:px-8 z-40 sticky top-0 transition-colors duration-300"
>
    
    <div class="flex items-center gap-4 sm:gap-6">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 dark:text-gray-400 hover:text-primary transition focus:outline-none lg:hidden bg-gray-50 dark:bg-gray-800 p-2 rounded-lg">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>

        <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:block text-gray-400 hover:text-primary transition focus:outline-none bg-gray-50 dark:bg-gray-800 p-2 rounded-lg hover:shadow-sm">
            <i class="fa-solid fa-bars-staggered text-lg"></i>
        </button>

        <div class="hidden sm:flex flex-col justify-center h-full">
            <h2 class="text-base sm:text-lg font-extrabold !text-gray-900 dark:text-white tracking-tight flex items-center gap-1.5">
                <span x-text="greeting"></span>, 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-light">
                    {{ explode(' ', auth()->user()->name ?? 'Pengguna')[0] }}
                </span>! <span class="origin-bottom-right animate-bounce inline-block">👋</span>
            </h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Selamat bekerja dan beraktivitas.</p>
        </div>
    </div>

    <div class="flex items-center gap-4 sm:gap-5">
        
        <div class="hidden md:flex flex-col items-end text-right">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-sm font-bold text-gray-900 dark:text-white tracking-wider" x-text="currentTime">--:--:--</span>
            </div>
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400" x-text="currentDate">Memuat tanggal...</span>
        </div>

        <div class="w-px h-8 bg-gray-200 dark:bg-gray-700 hidden md:block mx-1"></div>

        <button 
            @click="
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            "
            class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 hover:text-primary dark:hover:text-primary-light hover:shadow-sm transition-all shadow-inner shrink-0"
            title="Ganti Tema"
        >
            <i class="fa-solid fa-sun hidden dark:block text-yellow-400 text-lg"></i>
            <i class="fa-solid fa-moon block dark:hidden text-gray-600 text-lg"></i>
        </button>

        <a href="{{ route('pengaturan.akun') }}" wire:navigate title="Pengaturan Akun" class="block shrink-0">
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-gray-100 dark:border-gray-700 shadow-sm hover:border-primary dark:hover:border-primary-light transition-colors bg-gray-50 dark:bg-gray-800">
                @if(auth()->check() && auth()->user()->profile_photo)
                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profil" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-primary/20 dark:bg-primary/30 text-primary dark:text-primary-light flex items-center justify-center font-bold text-sm">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                @endif
            </div>
        </a>

    </div>

    <div 
        x-data="systemAssistant()" 
        x-init="startRoutine()"
        x-show="isVisible"
        x-transition:enter="transform ease-out duration-500 transition"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transform ease-in duration-300 transition"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0"
        class="fixed top-24 right-4 sm:right-6 w-80 bg-white dark:bg-card-dark rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden z-[100]"
        style="display: none;"
    >
        <div class="flex h-full">
            <div class="w-1.5 bg-primary shrink-0"></div>
            
            <div class="p-4 flex-1">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-3">
                        <div class="text-primary mt-0.5">
                            <i class="fa-solid fa-circle-info text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">UPBJ Assistant</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed" x-text="currentMessage"></p>
                        </div>
                    </div>
                    <button @click="isVisible = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 ml-2 focus:outline-none shrink-0">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            
            // 1. Script untuk Jam & Sapaan Header
            Alpine.data('headerWidget', () => ({
                currentTime: '',
                currentDate: '',
                greeting: 'Halo',
                
                initWidget() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);
                },

                updateClock() {
                    const now = new Date();
                    
                    const formatterHour = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', hour: 'numeric', hour12: false });
                    const hours = parseInt(formatterHour.format(now));
                    
                    if (hours >= 5 && hours < 11) {
                        this.greeting = 'Selamat Pagi';
                    } else if (hours >= 11 && hours < 15) {
                        this.greeting = 'Selamat Siang';
                    } else if (hours >= 15 && hours < 18) {
                        this.greeting = 'Selamat Sore';
                    } else {
                        this.greeting = 'Selamat Malam';
                    }

                    const formatterTime = new Intl.DateTimeFormat('id-ID', {
                        timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
                    });
                    this.currentTime = formatterTime.format(now).replace(/\./g, ':');

                    const formatterDate = new Intl.DateTimeFormat('id-ID', {
                        timeZone: 'Asia/Jakarta', weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
                    });
                    this.currentDate = formatterDate.format(now);
                }
            }));

            // 2. Script untuk Floating Popup UPBJ Assistant
            Alpine.data('systemAssistant', () => ({
                isVisible: false,
                currentIndex: 0,
                currentMessage: '',
                messages: [
                    'Tip: Gunakan menu Riwayat & Status untuk melacak proses pengadaan Anda.',
                    'Semoga harimu menyenangkan dan produktif!',
                    'Jangan lupa minum air putih agar tetap fokus.',
                    'Satu pekerjaan selesai adalah satu langkah kecil menuju kesuksesan.',
                    'Prioritas pekerjaan disusun secara otomatis oleh sistem.'
                ],
                
                startRoutine() {
                    setTimeout(() => {
                        this.showNextMessage();
                    }, 2000);

                    setInterval(() => {
                        this.showNextMessage();
                    }, 6000); 
                },
                
                showNextMessage() {
                    this.currentMessage = this.messages[this.currentIndex];
                    this.isVisible = true; 
                    
                    setTimeout(() => {
                        this.isVisible = false; 
                    }, 4500);

                    this.currentIndex = (this.currentIndex + 1) % this.messages.length;
                }
            }));
        })
    </script>
</header>