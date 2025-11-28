

<?php $__env->startSection('title', 'Rekening Bank - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Rekening Bank</h1>
        <p class="text-gray-600">Kelola rekening bank untuk pembayaran customer</p>
    </div>
    <a href="<?php echo e(route('admin.bank-accounts.create')); ?>" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition font-medium">
        <i class="fas fa-plus mr-2"></i> Tambah Rekening
    </a>
</div>

<?php if(session('success')): ?>
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
    <div class="flex">
        <i class="fas fa-check-circle text-green-500 mt-1"></i>
        <p class="ml-3 text-green-700"><?php echo e(session('success')); ?></p>
    </div>
</div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Urutan</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nama Bank</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nomor Rekening</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Atas Nama</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600"><?php echo e($account->display_order); ?></td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-university text-teal-600"></i>
                            </div>
                            <span class="font-medium text-gray-900"><?php echo e($account->bank_name); ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-800"><?php echo e($account->account_number); ?></td>
                    <td class="px-6 py-4 text-gray-600"><?php echo e($account->account_holder); ?></td>
                    <td class="px-6 py-4">
                        <?php if($account->is_active): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        <?php else: ?>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Nonaktif
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('admin.bank-accounts.edit', $account)); ?>" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="<?php echo e(route('admin.bank-accounts.destroy', $account)); ?>" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus rekening ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada rekening bank yang terdaftar
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/bank-accounts/index.blade.php ENDPATH**/ ?>