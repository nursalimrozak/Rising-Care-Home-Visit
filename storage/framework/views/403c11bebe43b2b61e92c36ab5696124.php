

<?php $__env->startSection('title', 'Daftar Pembayaran - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Pembayaran</h1>
        <p class="text-gray-600">Kelola semua pembayaran masuk</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">No. Pembayaran</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Layanan</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Metode</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        <?php echo e($payment->payment_number); ?>

                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?php echo e($payment->created_at->format('d M Y H:i')); ?>

                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?php echo e($payment->booking->customer->name ?? '-'); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e($payment->booking->booking_number ?? '-'); ?></div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?php if($payment->booking->packagePurchase): ?>
                            <span class="font-medium text-teal-600"><?php echo e($payment->booking->packagePurchase->package->name ?? 'Paket'); ?></span>
                            <div class="text-xs text-gray-500"><?php echo e($payment->booking->service->name ?? '-'); ?></div>
                        <?php else: ?>
                            <span class="font-medium text-blue-600">Reguler</span>
                            <div class="text-xs text-gray-500"><?php echo e($payment->booking->service->name ?? '-'); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm font-medium text-gray-900">
                        Rp <?php echo e(number_format($payment->total_amount, 0, ',', '.')); ?>

                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?php echo e(ucfirst($payment->payment_method)); ?>

                    </td>
                    <td class="px-6 py-4">
                        <?php if($payment->status == 'paid'): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                        <?php elseif($payment->status == 'pending'): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                        <?php elseif($payment->status == 'pending_verification'): ?>
                             <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Verifikasi</span>
                        <?php elseif($payment->status == 'failed'): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><?php echo e(ucfirst($payment->status)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <a href="<?php echo e(route('admin.payments.show', $payment)); ?>" class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                            Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        Belum ada data pembayaran
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php echo e($payments->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>