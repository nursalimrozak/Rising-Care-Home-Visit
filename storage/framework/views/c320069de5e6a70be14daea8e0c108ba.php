

<?php $__env->startSection('title', 'Booking Saya - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Booking Saya</h1>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <?php if($bookings->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-gray-600">No. Booking</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Layanan</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Jadwal</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Tipe</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Harga Est.</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-mono text-sm text-gray-600"><?php echo e($booking->booking_number); ?></td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-800"><?php echo e($booking->service->name ?? 'Layanan Tidak Tersedia'); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($booking->service ? $booking->service->category->name : '-'); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-800"><?php echo e(\Carbon\Carbon::parse($booking->scheduled_date)->format('d M Y')); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e(\Carbon\Carbon::parse($booking->scheduled_time)->format('H:i')); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($booking->booking_type == 'home_visit'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Home Visit
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Klinik
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $statusClasses = [
                                            'scheduled' => 'bg-yellow-100 text-yellow-800',
                                            'checked_in' => 'bg-blue-100 text-blue-800',
                                            'in_progress' => 'bg-purple-100 text-purple-800',
                                            'pending_payment' => 'bg-orange-100 text-orange-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'paid' => 'bg-teal-100 text-teal-800',
                                        ];
                                        $statusLabels = [
                                            'scheduled' => 'Terjadwal',
                                            'checked_in' => 'Check-in',
                                            'in_progress' => 'Diproses',
                                            'pending_payment' => 'Menunggu Pembayaran',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                            'paid' => 'Lunas',
                                        ];
                                    ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                                        <?php echo e($statusLabels[$booking->status] ?? ucfirst($booking->status)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    <div>Rp <?php echo e(number_format($booking->estimated_price, 0, ',', '.')); ?> <span class="text-xs text-gray-500">(Est)</span></div>
                                    <?php if($booking->final_price): ?>
                                        <div class="text-teal-600 font-bold mt-1">Rp <?php echo e(number_format($booking->final_price, 0, ',', '.')); ?> <span class="text-xs">(Final)</span></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="<?php echo e(route('booking.show', $booking)); ?>" class="text-teal-600 hover:text-teal-800 font-medium text-sm">Detail</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    <?php echo e($bookings->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada booking</h3>
                    <p class="text-gray-500 mb-6">Anda belum pernah melakukan booking layanan kesehatan.</p>
                    <a href="<?php echo e(route('booking.create')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        <i class="fas fa-plus mr-2"></i> Buat Booking Baru
                    </a>
                </div>
            <?php endif; ?>
        </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/booking/my-bookings.blade.php ENDPATH**/ ?>