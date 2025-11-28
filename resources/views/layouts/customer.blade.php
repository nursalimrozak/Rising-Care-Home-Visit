<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Dashboard - RisingCare')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-teal-800 text-white flex-shrink-0 hidden md:flex flex-col absolute md:relative z-20 h-full transition-all duration-300">
            <div class="p-4 border-b border-teal-700 flex items-center justify-between md:justify-center">
                <span class="text-2xl font-bold text-white">RisingCare</span>
                <button id="close-sidebar" class="md:hidden text-white focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto py-4">
                <nav class="space-y-1 px-2">
                    <a href="{{ route('customer.dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('customer.dashboard') ? 'bg-teal-900 text-white' : 'text-teal-100 hover:bg-teal-700 hover:text-white' }}">
                        <i class="fas fa-home mr-3 text-lg w-6 text-center"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('booking.my') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('booking.my') ? 'bg-teal-900 text-white' : 'text-teal-100 hover:bg-teal-700 hover:text-white' }}">
                        <i class="fas fa-calendar-check mr-3 text-lg w-6 text-center"></i>
                        Booking Saya
                    </a>

                    <a href="{{ route('booking.create') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('booking.create') ? 'bg-teal-900 text-white' : 'text-teal-100 hover:bg-teal-700 hover:text-white' }}">
                        <i class="fas fa-plus-circle mr-3 text-lg w-6 text-center"></i>
                        Buat Janji Baru
                    </a>

                    <a href="{{ route('customer.health-record.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('customer.health-record.*') ? 'bg-teal-900 text-white' : 'text-teal-100 hover:bg-teal-700 hover:text-white' }}">
                        <i class="fas fa-heartbeat mr-3 text-lg w-6 text-center"></i>
                        Rekap Kesehatan
                    </a>

                    <a href="{{ route('customer.packages.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('customer.packages.*') ? 'bg-teal-900 text-white' : 'text-teal-100 hover:bg-teal-700 hover:text-white' }}">
                        <i class="fas fa-box mr-3 text-lg w-6 text-center"></i>
                        Paket Saya
                    </a>

                    <div class="mt-8 mb-2 px-2 text-xs font-semibold text-teal-200 uppercase tracking-wider">
                        Akun
                    </div>

                    <a href="{{ route('customer.profile') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('customer.profile') ? 'bg-teal-900 text-white' : 'text-teal-100 hover:bg-teal-700 hover:text-white' }}">
                        <i class="fas fa-user mr-3 text-lg w-6 text-center"></i>
                        Profil Saya
                    </a>
                    <a href="#" class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-teal-100 hover:bg-teal-700 hover:text-white">
                        <i class="fas fa-cog mr-3 text-lg w-6 text-center"></i>
                        Pengaturan
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-teal-700 bg-teal-900">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if(Auth::user()->avatar)
                            <img class="h-8 w-8 rounded-full object-cover border-2 border-teal-200" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                        @else
                            <div class="h-8 w-8 rounded-full bg-teal-600 flex items-center justify-center text-white font-bold border-2 border-teal-200">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white truncate max-w-[120px]">{{ Auth::user()->name }}</p>
                        <p class="text-xs font-medium text-teal-200">{{ Auth::user()->membership->name ?? 'Customer' }}</p>
                    </div>
                </div>
                <form action="{{ route('keluar') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-red-600 hover:bg-red-700 transition">
                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-10 hidden md:hidden"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header -->
            <header class="bg-white shadow-sm z-10 md:hidden">
                <div class="px-4 py-3 flex items-center justify-between">
                    <span class="text-xl font-bold text-teal-600">RisingCare</span>
                    <button id="mobile-menu-button" class="text-gray-500 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </header>

            <!-- Content Body -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeSidebarButton = document.getElementById('close-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('hidden');
            sidebar.classList.toggle('flex');
            sidebarOverlay.classList.toggle('hidden');
        }

        mobileMenuButton.addEventListener('click', toggleSidebar);
        closeSidebarButton.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
