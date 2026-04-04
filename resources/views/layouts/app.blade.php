<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UPBJ POLMED - @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class', 
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        primary: '#7c3aed',         // Ungu Utama
                        'primary-light': '#8b5cf6', 
                        'primary-dark': '#6d28d9',  
                        base: '#f8f9fc',            // Background Light Mode
                        'base-dark': '#0f172a',     // Background Dark Mode
                        'card-dark': '#1e293b',     // Background Card di Dark Mode
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. FUNGSI TEMA (DARK/LIGHT MODE)
        function initTheme() {
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        // Panggil saat pertama kali web di-load
        initTheme();

        // Panggil ULANG setiap kali Livewire selesai pindah halaman via wire:navigate
        // INI KUNCI AGAR DARK MODE TIDAK HILANG
        document.addEventListener('livewire:navigated', () => {
            initTheme();
        });

        // 2. FUNGSI SWEETALERT TOAST (LIVEWIRE EVENT)
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('toast', (event) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: event[0].type, // 'success', 'error', 'warning', 'info'
                    title: event[0].message
                });
            });
        });
    </script>

    @livewireStyles
    <style> [x-cloak] { display: none !important; } </style>

    @vite(['resources/js/app.js'])
</head>

<body class="bg-base dark:bg-base-dark text-gray-900 dark:text-gray-100 transition-colors duration-300 font-sans" 
      x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

    <div class="flex h-screen overflow-hidden">
        
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-transition.opacity 
             class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden" x-cloak>
        </div>

        @include('components.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('components.header')

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 relative">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>