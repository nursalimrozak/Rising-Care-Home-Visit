<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', \App\Models\SiteSetting::where('key', 'site_name')->value('value') ?? 'RisingCare'); ?></title>
    
    <!-- Favicon -->
    <?php
        $favicon = \App\Models\SiteSetting::where('key', 'site_favicon')->value('value');
    ?>
    <?php if($favicon): ?>
        <link rel="icon" href="<?php echo e(asset('storage/' . $favicon)); ?>" type="image/x-icon">
    <?php endif; ?>

    <!-- Meta Tags -->
    <meta name="description" content="<?php echo e(\App\Models\SiteSetting::where('key', 'meta_description')->value('value') ?? ''); ?>">
    <meta name="keywords" content="<?php echo e(\App\Models\SiteSetting::where('key', 'meta_keywords')->value('value') ?? ''); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Swiper.js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="text-2xl font-bold text-blue-600">
                    <a href="<?php echo e(route('beranda')); ?>">
                        <?php
                            $siteLogo = \App\Models\SiteSetting::where('key', 'site_logo')->value('value');
                            $siteName = \App\Models\SiteSetting::where('key', 'site_name')->value('value') ?? 'RisingCare';
                        ?>
                        <?php if($siteLogo): ?>
                            <img src="<?php echo e(asset('storage/' . $siteLogo)); ?>" alt="<?php echo e($siteName); ?>" class="h-10 object-contain">
                        <?php else: ?>
                            <?php echo e($siteName); ?>

                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="<?php echo e(route('beranda')); ?>" class="text-sm font-medium <?php echo e(request()->routeIs('beranda') ? 'text-teal-600' : 'text-gray-600 hover:text-teal-600'); ?> transition">Beranda</a>
                    <a href="<?php echo e(route('layanan')); ?>" class="text-sm font-medium <?php echo e(request()->routeIs('layanan') ? 'text-teal-600' : 'text-gray-600 hover:text-teal-600'); ?> transition">Layanan</a>
                    <a href="<?php echo e(route('artikel')); ?>" class="text-sm font-medium <?php echo e(request()->routeIs('artikel') ? 'text-teal-600' : 'text-gray-600 hover:text-teal-600'); ?> transition">Artikel</a>
                </div>

                <!-- Desktop Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <?php if(auth()->guard()->check()): ?>
                        <?php
                            $dashboardRoute = auth()->user()->role == 'customer' ? route('customer.dashboard') : route('admin.dashboard');
                        ?>
                        <a href="<?php echo e($dashboardRoute); ?>" class="flex items-center gap-2 bg-teal-50 text-teal-700 px-5 py-2.5 rounded-full font-medium hover:bg-teal-100 transition">
                            <i class="fas fa-user-circle text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('masuk')); ?>" class="text-gray-600 font-medium hover:text-teal-600 transition px-4 py-2">Masuk</a>
                        <a href="<?php echo e(route('booking.create')); ?>" class="bg-teal-600 text-white px-6 py-2.5 rounded-full font-medium hover:bg-teal-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            Pesan Sekarang
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-teal-600 focus:outline-none">
                        <i class="fas fa-bars text-2xl" x-show="!mobileMenuOpen"></i>
                        <i class="fas fa-times text-2xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden bg-white border-t border-gray-100 shadow-lg absolute w-full left-0" 
             x-cloak>
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="<?php echo e(route('beranda')); ?>" class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('beranda') ? 'text-teal-600 bg-teal-50' : 'text-gray-700 hover:text-teal-600 hover:bg-gray-50'); ?>">Beranda</a>
                <a href="<?php echo e(route('layanan')); ?>" class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('layanan') ? 'text-teal-600 bg-teal-50' : 'text-gray-700 hover:text-teal-600 hover:bg-gray-50'); ?>">Layanan</a>
                <a href="<?php echo e(route('artikel')); ?>" class="block px-3 py-2 rounded-md text-base font-medium <?php echo e(request()->routeIs('artikel') ? 'text-teal-600 bg-teal-50' : 'text-gray-700 hover:text-teal-600 hover:bg-gray-50'); ?>">Artikel</a>
                
                <div class="pt-4 border-t border-gray-100 mt-2">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e($dashboardRoute); ?>" class="flex items-center justify-center gap-2 w-full bg-teal-50 text-teal-700 px-5 py-3 rounded-lg font-medium hover:bg-teal-100 transition">
                            <i class="fas fa-user-circle text-lg"></i>
                            <span>Dashboard</span>
                        </a>
                    <?php else: ?>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="<?php echo e(route('masuk')); ?>" class="flex items-center justify-center w-full border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition">Masuk</a>
                            <a href="<?php echo e(route('booking.create')); ?>" class="flex items-center justify-center w-full bg-teal-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-teal-700 transition shadow-md">
                                Pesan
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <?php echo $__env->yieldContent('content'); ?>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">
                        <?php if($siteLogo): ?>
                            <img src="<?php echo e(asset('storage/' . $siteLogo)); ?>" alt="<?php echo e($siteName); ?>" class="h-8 object-contain brightness-0 invert">
                        <?php else: ?>
                            <?php echo e($siteName); ?>

                        <?php endif; ?>
                    </h3>
                    <p class="text-gray-400"><?php echo e(\App\Models\SiteSetting::where('key', 'site_description')->value('value') ?? 'Layanan kesehatan terpercaya untuk keluarga Indonesia.'); ?></p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Kontak</h4>
                    <p class="text-gray-400"><i class="fas fa-phone mr-2"></i> <?php echo e(\App\Models\SiteSetting::where('key', 'contact_phone')->value('value') ?? '0812-3456-7890'); ?></p>
                    <p class="text-gray-400"><i class="fas fa-envelope mr-2"></i> <?php echo e(\App\Models\SiteSetting::where('key', 'contact_email')->value('value') ?? 'info@risingcare.com'); ?></p>
                    <p class="text-gray-400 mt-2"><i class="fas fa-map-marker-alt mr-2"></i> <?php echo e(\App\Models\SiteSetting::where('key', 'contact_address')->value('value') ?? 'Jl. Kesehatan No. 123, Jakarta'); ?></p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Link Cepat</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="<?php echo e(route('layanan')); ?>" class="hover:text-white">Layanan</a></li>
                        <li><a href="<?php echo e(route('artikel')); ?>" class="hover:text-white">Artikel</a></li>
                        <li><a href="<?php echo e(route('booking.create')); ?>" class="hover:text-white">Buat Janji</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Ikuti Kami</h4>
                    <div class="flex space-x-4">
                        <?php
                            $facebook = \App\Models\SiteSetting::where('key', 'social_facebook')->value('value');
                            $instagram = \App\Models\SiteSetting::where('key', 'social_instagram')->value('value');
                            $twitter = \App\Models\SiteSetting::where('key', 'social_twitter')->value('value');
                        ?>
                        
                        <?php if($facebook): ?>
                            <a href="<?php echo e($facebook); ?>" target="_blank" class="text-gray-400 hover:text-white"><i class="fab fa-facebook fa-2x"></i></a>
                        <?php endif; ?>
                        <?php if($instagram): ?>
                            <a href="<?php echo e($instagram); ?>" target="_blank" class="text-gray-400 hover:text-white"><i class="fab fa-instagram fa-2x"></i></a>
                        <?php endif; ?>
                        <?php if($twitter): ?>
                            <a href="<?php echo e($twitter); ?>" target="_blank" class="text-gray-400 hover:text-white"><i class="fab fa-twitter fa-2x"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo e(date('Y')); ?> <?php echo e($siteName); ?>. Made By KazoR Tam.</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH D:\Project\laragon\www\risingcare\resources\views/layouts/app.blade.php ENDPATH**/ ?>