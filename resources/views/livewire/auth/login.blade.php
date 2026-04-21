<div class="w-full max-w-4xl bg-white dark:bg-card-dark rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row relative z-10 transition-colors duration-300">
    
    @php
        $appName = \App\Models\Setting::where('key', 'app_name')->value('value') ?: 'UPBJ POLMED';
        $appLogo = \App\Models\Setting::where('key', 'app_logo')->value('value');
    @endphp

    <div class="w-full md:w-1/2 p-8 sm:p-12 relative flex flex-col justify-center">
        
        <button 
            onclick="
                let isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('color-theme', isDark ? 'dark' : 'light');
            "
            class="absolute top-6 left-6 p-2.5 rounded-full bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-primary transition border border-gray-100 dark:border-gray-700"
        >
            <i class="fa-solid fa-sun hidden dark:block text-yellow-400"></i>
            <i class="fa-solid fa-moon block dark:hidden"></i>
        </button>

        <div class="w-full max-w-sm mx-auto space-y-6">
            <div class="text-left mt-8">
                <div class="flex items-center gap-3 mb-6">
                    @if($appLogo)
                        <img src="{{ asset('storage/' . $appLogo) }}" alt="Logo {{ $appName }}" class="h-10 object-contain drop-shadow-sm">
                    @else
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Default" class="h-10 object-contain drop-shadow-sm">
                    @endif
                    
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $appName }}</span>
                </div>
                
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">Silakan Masuk</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Sistem Informasi Manajemen Pengadaan Barang & Jasa.
                </p>
            </div>

            <form wire:submit="authenticate" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="fa-regular fa-envelope text-gray-400"></i>
                        </div>
                        <input wire:model="email" id="email" type="email" required autofocus 
                            class="block w-full pl-11 pr-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-200 dark:border-gray-700 focus:ring-primary focus:border-primary' }} bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 outline-none transition-colors" 
                            placeholder="admin@polmed.ac.id">
                    </div>
                    @error('email') 
                        <span class="text-red-500 text-xs mt-1.5 block font-medium">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}
                        </span> 
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input wire:model="password" id="password" type="password" required 
                            class="block w-full pl-11 pr-4 py-3 rounded-xl border {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-200 dark:border-gray-700 focus:ring-primary focus:border-primary' }} bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 outline-none transition-colors"
                            placeholder="••••••••">
                    </div>
                    @error('password') 
                        <span class="text-red-500 text-xs mt-1.5 block font-medium">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}
                        </span> 
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer group">
                        <input wire:model="remember" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-primary focus:ring-primary dark:bg-gray-800 w-4 h-4 cursor-pointer transition">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-primary transition-colors">Ingat saya</span>
                    </label>
                    
                    <a href="#" class="text-sm font-medium text-primary hover:text-primary-dark transition-colors">
                        Lupa Sandi?
                    </a>
                </div>

                <button type="submit" 
                    class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl shadow-md shadow-primary/20 text-sm font-semibold text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all active:scale-[0.98] mt-4 disabled:opacity-70"
                    wire:loading.attr="disabled">
                    
                    <span wire:loading wire:target="authenticate" class="mr-2">
                        <i class="fa-solid fa-circle-notch fa-spin"></i>
                    </span>
                    
                    <span wire:loading.remove wire:target="authenticate">Masuk</span>
                    <span wire:loading wire:target="authenticate">Memverifikasi...</span>
                </button>
            </form>
            
            <div class="text-center mt-6">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    &copy; {{ date('Y') }} {{ $appName }}.<br>All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <div class="hidden md:flex md:w-1/2 bg-primary relative items-center justify-center overflow-hidden p-8">
        
        <div class="absolute w-[500px] h-[500px] bg-white/5 rounded-full border border-white/10 blur-[1px] -top-20 -right-20"></div>
        <div class="absolute w-[300px] h-[300px] bg-white/5 rounded-full border border-white/10 blur-[1px] bottom-10 left-10"></div>
        
        <div class="relative z-10 w-full max-w-sm flex flex-col items-center justify-center h-full">
            
            <div class="w-full bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 shadow-2xl p-6 mb-12">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                </div>
                
                <div class="space-y-4">
                    <div class="h-4 bg-white/20 rounded w-1/3"></div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-4 p-3 bg-white/5 rounded-lg border border-white/10">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex-shrink-0 flex items-center justify-center">
                                <i class="fa-solid fa-file-invoice text-white/70"></i>
                            </div>
                            <div class="w-full space-y-2">
                                <div class="h-3 bg-white/20 rounded w-1/2"></div>
                                <div class="h-2 bg-white/10 rounded w-3/4"></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-3 bg-white/5 rounded-lg border border-white/10">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex-shrink-0 flex items-center justify-center">
                                <i class="fa-solid fa-boxes-stacked text-white/70"></i>
                            </div>
                            <div class="w-full space-y-2">
                                <div class="h-3 bg-white/20 rounded w-1/3"></div>
                                <div class="h-2 bg-white/10 rounded w-2/3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div x-data="carouselData()" x-init="startCarousel()" class="text-center w-full min-h-[120px] flex flex-col justify-between">
                
                <div class="relative w-full overflow-hidden h-24 flex items-center justify-center">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="currentIndex === index" 
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-300 absolute w-full"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-4"
                             class="absolute w-full"
                             style="display: none;">
                            <h2 class="text-xl font-bold !text-white mb-2" x-text="slide.title"></h2>
                            <p class="text-sm text-white/90 leading-relaxed font-light px-2" x-text="slide.desc"></p>
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-center gap-2 mt-4">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="setSlide(index)" 
                                :class="{'w-4 h-1.5 bg-white': currentIndex === index, 'w-1.5 h-1.5 bg-white/30 hover:bg-white/50': currentIndex !== index}" 
                                class="rounded-full transition-all duration-300 focus:outline-none"></button>
                    </template>
                </div>
            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('carouselData', () => ({
                        currentIndex: 0,
                        timer: null,
                        slides: [
                            {
                                title: "Integritas Tanpa Kompromi",
                                desc: "Berkomitmen pada transparansi dan keadilan dalam setiap proses pengadaan barang dan jasa."
                            },
                            {
                                title: "Inovasi & Efisiensi Digital",
                                desc: "Pemanfaatan teknologi untuk mempercepat proses dan memberikan nilai terbaik bagi institusi."
                            },
                            {
                                title: "Kemitraan Strategis",
                                desc: "Membangun hubungan yang kuat dan saling menguntungkan berdasarkan kepatuhan hukum."
                            }
                        ],
                        startCarousel() {
                            this.timer = setInterval(() => {
                                this.nextSlide();
                            }, 4000); 
                        },
                        nextSlide() {
                            this.currentIndex = (this.currentIndex + 1) % this.slides.length;
                        },
                        setSlide(index) {
                            this.currentIndex = index;
                            clearInterval(this.timer);
                            this.startCarousel();
                        }
                    }));
                });
            </script>
            
            </div>
    </div>
</div>