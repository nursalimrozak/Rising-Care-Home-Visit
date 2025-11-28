<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - RisingCare')</title>
    
    <!-- Favicon -->
    @php
        $favicon = \App\Models\SiteSetting::where('key', 'site_favicon')->value('value');
    @endphp
    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
    @endif
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-4 border-b border-gray-700 flex items-center justify-center">
                @php
                    $siteLogo = \App\Models\SiteSetting::where('key', 'site_logo')->value('value');
                    $siteName = \App\Models\SiteSetting::where('key', 'site_name')->value('value') ?? 'RisingCare';
                @endphp
                
                @if($siteLogo)
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-10 object-contain">
                @else
                    <span class="text-2xl font-bold text-teal-400">{{ $siteName }}</span>
                @endif
            </div>
            
            <div class="flex-1 overflow-y-auto py-4" x-data="{ 
                activeMenu: '{{ 
                    request()->routeIs('admin.bookings.*') || request()->routeIs('admin.users.*') || request()->routeIs('admin.occupations.*') || request()->routeIs('admin.memberships.*') ? 'manajemen' : 
                    (request()->routeIs('admin.services.*') || request()->routeIs('admin.service-categories.*') || request()->routeIs('admin.health-screening.*') || request()->routeIs('admin.service-addons.*') || request()->routeIs('admin.package-prices.*') || request()->routeIs('admin.bank-accounts.*') ? 'layanan' : 
                    (request()->routeIs('admin.landing.*') || request()->routeIs('admin.reviews.*') || request()->routeIs('admin.settings') ? 'cms' : 
                    (request()->routeIs('admin.payments.*') || request()->routeIs('admin.commissions.*') || request()->routeIs('admin.payouts.*') || request()->routeIs('admin.commission-settings.*') ? 'keuangan' : 
                    (request()->routeIs('admin.activity-logs.*') ? 'system' : ''))))
                }}',
                toggle(menu) {
                    this.activeMenu = (this.activeMenu === menu) ? '' : menu;
                }
            }">
                <nav class="space-y-1 px-2">
                    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <i class="fas fa-tachometer-alt mr-3 text-lg w-6 text-center"></i>
                        Dashboard
                    </a>

                    <!-- Manajemen Menu -->
                    <div>
                        <button @click="toggle('manajemen')" class="w-full flex items-center justify-between px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none">
                            <span class="flex items-center">
                                <i class="fas fa-tasks mr-3 text-lg w-6 text-center"></i>
                                Manajemen
                            </span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'transform rotate-180': activeMenu === 'manajemen'}"></i>
                        </button>
                        <div x-show="activeMenu === 'manajemen'" x-collapse class="space-y-1 pl-4 mt-1 bg-gray-900 rounded-md py-2">
                            <a href="{{ route('admin.bookings.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.bookings.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-calendar-check mr-3 w-5 text-center"></i> Bookings
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-users mr-3 w-5 text-center"></i> Users
                            </a>
                            <a href="{{ route('admin.occupations.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.occupations.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-user-md mr-3 w-5 text-center"></i> Occupations
                            </a>
                            <a href="{{ route('admin.memberships.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.memberships.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-id-card mr-3 w-5 text-center"></i> Memberships
                            </a>
                        </div>
                    </div>


                    <!-- Layanan Menu -->
                    <div>
                        <button @click="toggle('layanan')" class="w-full flex items-center justify-between px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none">
                            <span class="flex items-center">
                                <i class="fas fa-concierge-bell mr-3 text-lg w-6 text-center"></i>
                                Layanan
                            </span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'transform rotate-180': activeMenu === 'layanan'}"></i>
                        </button>
                        <div x-show="activeMenu === 'layanan'" x-collapse class="space-y-1 pl-4 mt-1 bg-gray-900 rounded-md py-2">
                            <a href="{{ route('admin.services.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.services.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-stethoscope mr-3 w-5 text-center"></i> Services
                            </a>
                            <a href="{{ route('admin.service-categories.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.service-categories.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-tags mr-3 w-5 text-center"></i> Categories
                            </a>
                            <a href="{{ route('admin.health-screening.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.health-screening.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-notes-medical mr-3 w-5 text-center"></i> Rekap Kesehatan
                            </a>
                            <a href="{{ route('admin.service-addons.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.service-addons.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-plus-square mr-3 w-5 text-center"></i> Add-ons Layanan
                            </a>
                            <a href="{{ route('admin.package-prices.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.package-prices.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-box-open mr-3 w-5 text-center"></i> Harga Paket
                            </a>
                            <a href="{{ route('admin.bank-accounts.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.bank-accounts.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-university mr-3 w-5 text-center"></i> Rekening Bank
                            </a>
                        </div>
                    </div>

                    <!-- CMS Landing Page Menu -->
                    <div>
                        <button @click="toggle('cms')" class="w-full flex items-center justify-between px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none">
                            <span class="flex items-center">
                                <i class="fas fa-desktop mr-3 text-lg w-6 text-center"></i>
                                CMS Landing Page
                            </span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'transform rotate-180': activeMenu === 'cms'}"></i>
                        </button>
                        <div x-show="activeMenu === 'cms'" x-collapse class="space-y-1 pl-4 mt-1 bg-gray-900 rounded-md py-2">
                            <a href="{{ route('admin.landing.hero-slides.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.landing.hero-slides.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-images mr-3 w-5 text-center"></i> Hero Slides
                            </a>
                            <a href="{{ route('admin.landing.about.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.landing.about.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-info-circle mr-3 w-5 text-center"></i> About Section
                            </a>
                            <a href="{{ route('admin.landing.services-highlight.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.landing.services-highlight.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-star mr-3 w-5 text-center"></i> Services Highlight
                            </a>
                            <a href="{{ route('admin.landing.how-we-work.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.landing.how-we-work.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-list-ol mr-3 w-5 text-center"></i> How We Work
                            </a>
                            <a href="{{ route('admin.reviews.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.reviews.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-comment-alt mr-3 w-5 text-center"></i> Testimonials (Reviews)
                            </a>
                            <a href="{{ route('admin.landing.articles.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.landing.articles.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-newspaper mr-3 w-5 text-center"></i> Articles
                            </a>
                            <a href="{{ route('admin.landing.cta-appointment.edit') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.landing.cta-appointment.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-bullhorn mr-3 w-5 text-center"></i> CTA Section
                            </a>
                            <a href="{{ route('admin.settings') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.settings') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-cog mr-3 w-5 text-center"></i> Site Settings
                            </a>
                        </div>
                    </div>

                    <!-- Keuangan Menu -->
                    <div>
                        <button @click="toggle('keuangan')" class="w-full flex items-center justify-between px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none">
                            <span class="flex items-center">
                                <i class="fas fa-wallet mr-3 text-lg w-6 text-center"></i>
                                Keuangan
                            </span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'transform rotate-180': activeMenu === 'keuangan'}"></i>
                        </button>
                        <div x-show="activeMenu === 'keuangan'" x-collapse class="space-y-1 pl-4 mt-1 bg-gray-900 rounded-md py-2">
                            <a href="{{ route('admin.payments.index') }}" @click.stop class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.payments.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-money-bill-wave mr-3 w-5 text-center"></i> Payments
                            </a>
                            <a href="{{ route('admin.commissions.index') }}" @click.stop class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.commissions.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-percentage mr-3 w-5 text-center"></i> Komisi
                            </a>
                            <a href="{{ route('admin.payouts.index') }}" @click.stop class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.payouts.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-hand-holding-usd mr-3 w-5 text-center"></i> Payouts
                            </a>
                            <a href="{{ route('admin.commission-settings.index') }}" @click.stop class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.commission-settings.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-sliders-h mr-3 w-5 text-center"></i> Pengaturan Komisi
                            </a>
                        </div>
                    </div>

                    <!-- System Menu -->
                    <div>
                        <button @click="toggle('system')" class="w-full flex items-center justify-between px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none">
                            <span class="flex items-center">
                                <i class="fas fa-cogs mr-3 text-lg w-6 text-center"></i>
                                System
                            </span>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{'transform rotate-180': activeMenu === 'system'}"></i>
                        </button>
                        <div x-show="activeMenu === 'system'" x-collapse class="space-y-1 pl-4 mt-1 bg-gray-900 rounded-md py-2">
                            <a href="{{ route('admin.activity-logs.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.activity-logs.*') ? 'text-white bg-gray-800' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                                <i class="fas fa-history mr-3 w-5 text-center"></i> Log Aktivitas
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center text-white font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs font-medium text-gray-400">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                </div>
                <form action="{{ route('keluar') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Mobile Header -->
            <header class="bg-white shadow-sm z-10 md:hidden">
                <div class="px-4 py-3 flex items-center justify-between">
                    <span class="text-xl font-bold text-teal-600">RisingCare</span>
                    <button class="text-gray-500 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </header>

            <!-- Content Body -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success'))
                    <div id="successAlert" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Sukses</p>
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div id="errorAlert" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Terjadi Kesalahan</p>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Auto-dismiss alerts after 3 seconds
        setTimeout(function() {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            
            if (successAlert) {
                successAlert.style.transition = 'opacity 0.5s ease-out';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }
            
            if (errorAlert) {
                errorAlert.style.transition = 'opacity 0.5s ease-out';
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 500);
            }
        }, 3000);
    </script>
</body>
</html>
