

<?php $__env->startSection('title', 'Kelola Harga Paket - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Kelola Harga Paket Layanan</h1>
    <p class="text-gray-600 mt-1">Atur harga paket (Reguler, Eksekutif, VIP, Premium) untuk setiap layanan dan membership</p>
</div>

<?php if(session('success')): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p><?php echo e(session('success')); ?></p>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Layanan</th>
                    <th class="px-6 py-4 text-left font-semibold text-gray-600">Kategori</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600">Status Harga</th>
                    <th class="px-6 py-4 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900"><?php echo e($service->name); ?></div>
                        <div class="text-sm text-gray-500"><?php echo e($service->service_type); ?></div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <?php echo e($service->category->name); ?>

                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if($service->packagePrices->count() > 0): ?>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                <?php echo e($service->packagePrices->count()); ?> harga tersedia
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                Belum ada harga
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="<?php echo e(route('admin.package-prices.edit', $service)); ?>" 
                           class="inline-flex items-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-medium">
                            <i class="fas fa-edit mr-2"></i> Atur Harga
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p>Belum ada layanan tersedia</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/package-prices/index.blade.php ENDPATH**/ ?>