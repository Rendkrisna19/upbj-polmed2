<div class="min-h-screen flex flex-col lg:flex-row w-full bg-white dark:bg-base-dark transition-colors duration-300">
    
    <div class="w-full lg:w-5/12 flex items-center justify-center p-8 sm:p-12 lg:p-16 relative z-10 bg-white dark:bg-base-dark shadow-2xl lg:shadow-none">
        
        <button 
            onclick="
                let isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('color-theme', isDark ? 'dark' : 'light');
            "
            class="absolute top-6 left-6 p-2 rounded-full bg-gray-100 dark:bg-card-dark text-gray-500 dark:text-gray-400 hover:text-primary transition"
        >
            <i class="fa-solid fa-sun hidden dark:block text-yellow-400"></i>
            <i class="fa-solid fa-moon block dark:hidden"></i>
        </button>

        <div class="w-full max-w-md space-y-8">
            <div class="text-center">
                <img src="https://i0.wp.com/tambahpinter.com/wp-content/uploads/2020/04/Logo-POLMED-1536x1449.png" alt="Logo Polmed" class="h-24 mx-auto mb-6 object-contain drop-shadow-sm">
                
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">UPBJ POLMED732647</h1>
                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 leading-relaxed px-4">
                    Sistem Informasi Manajemen Pengadaan Barang dan Jasa <br class="hidden sm:block"> Politeknik Negeri Medan.
                </p>
            </div>

            <div class="bg-white dark:bg-card-dark rounded-3xl shadow-none lg:shadow-sm lg:border border-gray-100 dark:border-gray-800 p-0 lg:p-8">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6 text-center lg:text-left">Silakan Masuk</h2>

                <form wire:submit="authenticate" class="space-y-5">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email Pengguna</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-400"></i>
                            </div>
                            <input wire:model="email" id="email" type="email" required autofocus 
                                class="block w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary focus:border-primary transition-colors" 
                                placeholder="admin@polmed.ac.id">
                        </div>
                        @error('email') <span class="text-red-500 text-xs mt-1.5 block font-medium"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Kata Sandi</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-400"></i>
                            </div>
                            <input wire:model="password" id="password" type="password" required 
                                class="block w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary focus:border-primary transition-colors"
                                placeholder="••••••••">
                        </div>
                        @error('password') <span class="text-red-500 text-xs mt-1.5 block font-medium"><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="flex items-center cursor-pointer group">
                            <input wire:model="remember" type="checkbox" class="rounded border-gray-300 dark:border-gray-600 text-primary focus:ring-primary dark:bg-gray-800 w-4 h-4 cursor-pointer transition">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400 group-hover:text-primary transition-colors">Ingat sesi saya</span>
                        </label>
                    </div>

                    <button type="submit" 
                        class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl shadow-sm text-sm font-semibold text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all active:scale-[0.98] mt-6 disabled:opacity-70"
                        wire:loading.attr="disabled">
                        
                        <span wire:loading wire:target="authenticate" class="mr-2">
                            <i class="fa-solid fa-circle-notch fa-spin"></i>
                        </span>
                        
                        <span wire:loading.remove wire:target="authenticate"><i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Masuk ke Sistem</span>
                        <span wire:loading wire:target="authenticate">Memverifikasi Data...</span>
                    </button>
                </form>
            </div>
            
            <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-8">
                &copy; {{ date('Y') }} Politeknik Negeri Medan. All rights reserved.
            </p>
        </div>
    </div>

    <div class="hidden lg:flex lg:w-7/12 relative items-center justify-center overflow-hidden">
        
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transform scale-105 transition-transform duration-1000" 
             style="background-image: url('https://lpmneraca.com/wp-content/uploads/2023/06/polmed.jpg');">
        </div>
        
        <div class="absolute inset-0 bg-gradient-to-br from-primary/90 via-primary-dark/80 to-gray-900/90 mix-blend-multiply"></div>
        
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>

        <div class="relative z-10 p-16 max-w-2xl text-center">
            <div class="w-20 h-20 mx-auto bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 mb-8 shadow-2xl">
                <i class="fa-solid fa-boxes-packing text-4xl text-white"></i>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">
                Efisiensi & <span class="text-purple-200">Transparansi</span>
            </h2>
            <p class="text-lg text-white/80 leading-relaxed font-light">
                Platform digital terintegrasi untuk mempermudah proses pengajuan, pengerjaan, hingga pelaporan realisasi pengadaan barang dan jasa secara *real-time* di lingkungan Politeknik Negeri Medan.
            </p>
        </div>
    </div>

</div>