

<?php $__env->startSection('title', 'Dashboard - Admin RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600">Selamat datang kembali, <?php echo e(Auth::user()->name); ?>!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm text-gray-500 font-medium">Booking Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo e($stats['total_bookings_today']); ?></p>
            </div>
            <div class="bg-blue-100 p-3 rounded-full">
                <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
            </div>
        </div>
        <div class="text-sm text-gray-500">
            <span class="text-green-500 font-medium"><i class="fas fa-arrow-up"></i> <?php echo e($stats['total_bookings_week']); ?></span> minggu ini
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-teal-500">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Customer</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo e($stats['total_customers']); ?></p>
            </div>
            <div class="bg-teal-100 p-3 rounded-full">
                <i class="fas fa-users text-teal-600 text-xl"></i>
            </div>
        </div>
        <div class="text-sm text-gray-500">
            Aktif terdaftar
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm text-gray-500 font-medium">Pending Payment</p>
                <p class="text-2xl font-bold text-gray-800"><?php echo e($stats['pending_payments']); ?></p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
        <div class="text-sm text-gray-500">
            Perlu verifikasi
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm text-gray-500 font-medium">Pendapatan Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-800">Rp <?php echo e(number_format($stats['total_revenue_month'], 0, ',', '.')); ?></p>
            </div>
            <div class="bg-green-100 p-3 rounded-full">
                <i class="fas fa-wallet text-green-600 text-xl"></i>
            </div>
        </div>
        <div class="text-sm text-gray-500">
            <?php echo e(date('F Y')); ?>

        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Bookings -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Booking Terbaru</h3>
            <a href="#" class="text-teal-600 text-sm hover:underline">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Layanan</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Jadwal</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800"><?php echo e($booking->customer?->name ?? 'N/A'); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($booking->booking_number); ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?php echo e($booking->service?->name ?? 'N/A'); ?>

                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?php echo e(\Carbon\Carbon::parse($booking->scheduled_date)->format('d/m/Y')); ?>

                            <br>
                            <span class="text-xs"><?php echo e(\Carbon\Carbon::parse($booking->scheduled_time)->format('H:i')); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $statusColors = [
                                    'scheduled' => 'bg-yellow-100 text-yellow-800',
                                    'checked_in' => 'bg-blue-100 text-blue-800',
                                    'in_progress' => 'bg-purple-100 text-purple-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    'paid' => 'bg-teal-100 text-teal-800',
                                ];
                            ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo e($statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e(ucfirst($booking->status)); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada booking terbaru</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Payments -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Perlu Verifikasi</h3>
            <a href="#" class="text-teal-600 text-sm hover:underline">Lihat Semua</a>
        </div>
        <div class="divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $pendingPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-medium text-gray-800"><?php echo e($payment->booking->customer->name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($payment->payment_number); ?></p>
                    </div>
                    <span class="font-bold text-teal-600">Rp <?php echo e(number_format($payment->total_amount, 0, ',', '.')); ?></span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded"><?php echo e($payment->payment_method); ?></span>
                    <button class="text-xs bg-blue-50 text-blue-600 px-3 py-1 rounded hover:bg-blue-100 font-medium">
                        Verifikasi
                    </button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-6 text-center text-gray-500">
                Tidak ada pembayaran pending
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>