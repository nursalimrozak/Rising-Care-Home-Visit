

<?php $__env->startSection('title', 'Payouts - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Payout</h1>
        <p class="text-gray-600">Riwayat pembayaran komisi mingguan</p>
    </div>
    <!-- Optional: Button to trigger manual payout generation if needed -->
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Periode</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Penerima</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total Komisi</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Biaya Admin</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Net Payout</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <?php echo e($payout->period_start->format('d M')); ?> - <?php echo e($payout->period_end->format('d M Y')); ?>

                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900"><?php echo e($payout->user->name); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e(ucfirst($payout->user->role)); ?></div>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm text-gray-600">
                        Rp <?php echo e(number_format($payout->amount, 0, ',', '.')); ?>

                    </td>
                    <td class="px-6 py-4 font-mono text-sm text-red-600">
                        - Rp <?php echo e(number_format($payout->fee, 0, ',', '.')); ?>

                    </td>
                    <td class="px-6 py-4 font-mono text-sm font-bold text-green-600">
                        Rp <?php echo e(number_format($payout->net_amount, 0, ',', '.')); ?>

                    </td>
                    <td class="px-6 py-4">
                        <?php if($payout->status == 'processed'): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Processed
                            </span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <a href="<?php echo e(route('admin.payouts.show', $payout)); ?>" class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                            Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        Belum ada data payout
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php echo e($payouts->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/payouts/index.blade.php ENDPATH**/ ?>