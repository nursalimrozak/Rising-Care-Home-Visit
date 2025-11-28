

<?php $__env->startSection('title', 'Log Aktivitas'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Log Aktivitas</h1>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <form action="<?php echo e(route('admin.activity-logs.index')); ?>" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
                <select name="action" class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                    <option value="">Semua Aksi</option>
                    <option value="LOGIN" <?php echo e(request('action') == 'LOGIN' ? 'selected' : ''); ?>>Login</option>
                    <option value="LOGOUT" <?php echo e(request('action') == 'LOGOUT' ? 'selected' : ''); ?>>Logout</option>
                    <option value="CREATE" <?php echo e(request('action') == 'CREATE' ? 'selected' : ''); ?>>Create</option>
                    <option value="UPDATE" <?php echo e(request('action') == 'UPDATE' ? 'selected' : ''); ?>>Update</option>
                    <option value="DELETE" <?php echo e(request('action') == 'DELETE' ? 'selected' : ''); ?>>Delete</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="date" value="<?php echo e(request('date')); ?>" class="w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 transition">
                    Filter
                </button>
                <?php if(request()->anyFilled(['action', 'date'])): ?>
                    <a href="<?php echo e(route('admin.activity-logs.index')); ?>" class="ml-2 text-gray-600 hover:text-gray-800 px-4 py-2">
                        Reset
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($log->created_at->format('d M Y H:i:s')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($log->user->name ?? 'System/Guest'); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($log->user->role ?? '-'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo e($log->action === 'DELETE' ? 'bg-red-100 text-red-800' : 
                                       ($log->action === 'CREATE' ? 'bg-green-100 text-green-800' : 
                                       ($log->action === 'UPDATE' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'))); ?>">
                                    <?php echo e($log->action); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?php echo e($log->description); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($log->ip_address); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Belum ada aktivitas yang tercatat.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($logs->withQueryString()->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/activity-logs/index.blade.php ENDPATH**/ ?>