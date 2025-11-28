

<?php $__env->startSection('title', 'Komisi Saya - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Komisi Saya</h1>
    <p class="text-gray-600">Riwayat pendapatan komisi dari layanan</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Komisi Diterima</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp <?php echo e(number_format($totalCommission, 0, ',', '.')); ?></h3>
            </div>
            <div class="p-3 bg-green-100 rounded-full text-green-600">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Komisi Pending</p>
                <h3 class="text-2xl font-bold text-gray-800">Rp <?php echo e(number_format($pendingCommission, 0, ',', '.')); ?></h3>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Booking</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?php echo e($commission->created_at->format('d M Y H:i')); ?>

                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            <?php echo e($commission->payment->booking->booking_number ?? '-'); ?>

                        </div>
                        <div class="text-xs text-gray-500">
                            <?php echo e($commission->payment->payment_number ?? '-'); ?>

                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm font-medium text-gray-900">
                        Rp <?php echo e(number_format($commission->amount, 0, ',', '.')); ?>

                    </td>
                    <td class="px-6 py-4">
                        <?php if($commission->status == 'paid'): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Paid
                            </span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        Belum ada riwayat komisi
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php echo e($commissions->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/petugas/commissions/index.blade.php ENDPATH**/ ?>