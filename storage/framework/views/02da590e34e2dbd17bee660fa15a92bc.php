

<?php $__env->startSection('title', 'Dashboard Customer - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Health Screening Reminder Modal -->
    <?php if(isset($hasHealthRecord) && !$hasHealthRecord): ?>
    <div x-data="{ showModal: true }" x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 overflow-hidden transform transition-all">
            <div class="bg-teal-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-heartbeat"></i> Rekap Kesehatan
                </h3>
                <button @click="showModal = false" class="text-teal-100 hover:text-white transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 text-center">
                <div class="mb-4 bg-teal-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-clipboard-list text-3xl text-teal-600"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-800 mb-2">Lengkapi Data Kesehatan Anda</h4>
                <p class="text-gray-600 mb-6">
                    Anda belum mengisi rekap kesehatan. Data ini penting agar kami dapat memberikan layanan perawatan yang tepat dan aman untuk Anda.
                </p>
                <div class="flex flex-col gap-3">
                    <a href="<?php echo e(route('customer.health-record.index')); ?>" class="w-full bg-teal-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-teal-700 transition shadow-lg flex items-center justify-center gap-2">
                        Isi Rekap Kesehatan Sekarang <i class="fas fa-arrow-right"></i>
                    </a>
                    <button @click="showModal = false" class="text-gray-500 hover:text-gray-700 font-medium text-sm">
                        Nanti Saja
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="max-w-7xl mx-auto">
        <!-- Welcome Section -->
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8 border border-gray-100">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="relative">
                    <?php if($user->avatar): ?>
                        <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" class="w-24 h-24 rounded-full object-cover border-4 border-teal-50">
                    <?php else: ?>
                        <div class="w-24 h-24 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 text-3xl font-bold border-4 border-teal-50">
                            <?php echo e(substr($user->name, 0, 1)); ?>

                        </div>
                    <?php endif; ?>
                    <?php if($user->membership): ?>
                        <div class="absolute -bottom-2 -right-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full border-2 border-white shadow-sm">
                            <?php echo e($user->membership->name); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Halo, <?php echo e($user->name); ?>! 👋</h1>
                    <p class="text-gray-600">Selamat datang kembali di RisingCare. Bagaimana kesehatan Anda hari ini?</p>
                </div>
                <div class="flex gap-3">
                    <a href="<?php echo e(route('booking.create')); ?>" class="bg-teal-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-teal-700 transition shadow-lg shadow-teal-200 flex items-center">
                        <i class="fas fa-plus-circle mr-2"></i> Buat Janji Baru
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Stats & Membership -->
            <div class="space-y-8">
                <!-- Loyalty Points Card -->
                <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-20 rounded-full blur-xl"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-yellow-100 font-medium mb-1">Loyalty Points</p>
                                <h3 class="text-4xl font-bold"><?php echo e($user->loyalty_points); ?></h3>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <i class="fas fa-star text-2xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-yellow-50">Kumpulkan poin untuk mendapatkan diskon layanan!</p>
                    </div>
                </div>

                <!-- Membership Card -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-id-card text-teal-600 mr-2"></i> Status Membership
                    </h3>
                    <?php if($user->membership): ?>
                        <div class="text-center py-4">
                            <div class="inline-block p-4 rounded-full bg-yellow-50 mb-3">
                                <i class="fas fa-crown text-3xl text-yellow-500"></i>
                            </div>
                            <h4 class="text-xl font-bold text-gray-800"><?php echo e($user->membership->name); ?></h4>
                            <p class="text-gray-500 text-sm mt-1">Nikmati harga spesial untuk member <?php echo e($user->membership->name); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-3">Anda belum memiliki membership.</p>
                            <a href="#" class="text-teal-600 font-medium hover:underline">Pelajari cara upgrade</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Profile Quick View -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <i class="fas fa-user text-teal-600 mr-2"></i> Profil Saya
                        </h3>
                        <a href="<?php echo e(route('customer.profile')); ?>" class="text-sm text-teal-600 hover:underline">Edit</a>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600">
                            <div class="w-8 text-center"><i class="fas fa-envelope text-gray-400"></i></div>
                            <span class="text-sm"><?php echo e($user->email); ?></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <div class="w-8 text-center"><i class="fas fa-phone text-gray-400"></i></div>
                            <span class="text-sm"><?php echo e($user->phone); ?></span>
                        </div>
                        <div class="flex items-start text-gray-600">
                            <div class="w-8 text-center mt-1"><i class="fas fa-map-marker-alt text-gray-400"></i></div>
                            <span class="text-sm"><?php echo e($user->address ?? 'Belum ada alamat'); ?></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <div class="w-8 text-center"><i class="fas fa-briefcase text-gray-400"></i></div>
                            <span class="text-sm"><?php echo e($user->occupation->name ?? '-'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Bookings -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Upcoming Bookings -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-teal-50/50">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <i class="fas fa-calendar-check text-teal-600 mr-2"></i> Jadwal Mendatang
                        </h3>
                        <?php if($upcomingBookings->count() > 0): ?>
                            <span class="bg-teal-100 text-teal-700 text-xs font-bold px-2 py-1 rounded-full">
                                <?php echo e($upcomingBookings->count()); ?> Janji
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($upcomingBookings->count() > 0): ?>
                        <div class="divide-y divide-gray-100">
                            <?php $__currentLoopData = $upcomingBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-6 hover:bg-gray-50 transition">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="bg-teal-100 text-teal-600 rounded-xl p-3 text-center min-w-[60px]">
                                            <div class="text-xs font-bold uppercase"><?php echo e(\Carbon\Carbon::parse($booking->scheduled_date)->format('M')); ?></div>
                                            <div class="text-xl font-bold"><?php echo e(\Carbon\Carbon::parse($booking->scheduled_date)->format('d')); ?></div>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800"><?php echo e($booking->service->name ?? 'Layanan Tidak Tersedia'); ?></h4>
                                            <div class="flex items-center text-sm text-gray-500 mt-1">
                                                <i class="far fa-clock mr-2"></i>
                                                <?php echo e(\Carbon\Carbon::parse($booking->scheduled_time)->format('H:i')); ?> WIB
                                            </div>
                                            <div class="flex items-center text-sm text-gray-500 mt-1">
                                                <i class="fas fa-map-marker-alt mr-2"></i>
                                                <?php echo e($booking->type == 'home_visit' ? 'Kunjungan Rumah' : 'Klinik'); ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <?php echo e(ucfirst($booking->status)); ?>

                                        </span>
                                        <a href="#" class="text-gray-400 hover:text-teal-600"><i class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="p-8 text-center">
                            <div class="inline-block p-4 rounded-full bg-gray-50 mb-3">
                                <i class="far fa-calendar-times text-3xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Tidak ada jadwal mendatang</p>
                            <a href="<?php echo e(route('booking.create')); ?>" class="text-teal-600 text-sm hover:underline mt-2 inline-block">Buat janji sekarang</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recent History -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <i class="fas fa-history text-gray-400 mr-2"></i> Riwayat Terakhir
                        </h3>
                        <a href="<?php echo e(route('booking.my')); ?>" class="text-sm text-teal-600 hover:underline">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Layanan</th>
                                    <th class="px-6 py-3 font-medium">Tanggal</th>
                                    <th class="px-6 py-3 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-gray-800"><?php echo e($booking->service->name ?? 'Layanan Tidak Tersedia'); ?></span>
                                        <div class="text-xs text-gray-500"><?php echo e($booking->booking_number); ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?php echo e(\Carbon\Carbon::parse($booking->scheduled_date)->format('d M Y')); ?>

                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                            $statusClass = match($booking->status) {
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        ?>
                                        <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($statusClass); ?>">
                                            <?php echo e(ucfirst($booking->status)); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                        Belum ada riwayat booking
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/customer/dashboard.blade.php ENDPATH**/ ?>