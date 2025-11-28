

<?php $__env->startSection('title', 'Detail User - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="<?php echo e(route('admin.users.index')); ?>" class="bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail User</h1>
            <p class="text-gray-600">Informasi lengkap pengguna</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- User Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden h-fit">
        <div class="bg-teal-600 h-24"></div>
        <div class="px-6 pb-6 relative">
            <div class="-mt-12 mb-4 flex justify-between items-end">
                <div class="h-24 w-24 rounded-full bg-white p-1 shadow-lg">
                    <?php if($user->avatar): ?>
                        <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" class="h-full w-full rounded-full object-cover">
                    <?php else: ?>
                        <div class="h-full w-full rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-3xl font-bold">
                            <?php echo e(substr($user->name, 0, 1)); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <div class="mb-2">
                    <?php if($user->role == 'customer' && $user->membership): ?>
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                            <i class="fas fa-crown mr-1"></i> <?php echo e($user->membership->name); ?>

                        </span>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <?php echo e(ucfirst(str_replace('_', ' ', $user->role))); ?>

                        </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <h2 class="text-xl font-bold text-gray-800"><?php echo e($user->name); ?></h2>
            <p class="text-gray-500 mb-4"><?php echo e(optional($user->occupation)->name ?? 'User'); ?></p>
            
            <div class="space-y-3 border-t pt-4">
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-envelope w-6 text-center mr-2 text-teal-600"></i>
                    <span><?php echo e($user->email); ?></span>
                </div>
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-phone w-6 text-center mr-2 text-teal-600"></i>
                    <a href="https://wa.me/<?php echo e(preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $user->phone))); ?>" target="_blank" class="hover:text-teal-600 hover:underline">
                        <?php echo e($user->phone); ?> <i class="fas fa-external-link-alt text-xs ml-1"></i>
                    </a>
                </div>
                <?php if($user->address): ?>
                <div class="flex items-start text-gray-600">
                    <i class="fas fa-map-marker-alt w-6 text-center mr-2 text-teal-600 mt-1"></i>
                    <span><?php echo e($user->address); ?></span>
                </div>
                <?php endif; ?>
                <div class="flex items-center text-gray-600">
                    <i class="fas fa-calendar-alt w-6 text-center mr-2 text-teal-600"></i>
                    <span>Joined <?php echo e($user->created_at->format('d M Y')); ?></span>
                </div>
            </div>

            <?php if($user->role != 'customer' && $user->payoutDetail): ?>
            <div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-100">
                <div class="flex items-center mb-3">
                    <i class="fas fa-university text-blue-600 text-lg mr-2"></i>
                    <span class="text-blue-800 font-semibold">Informasi Rekening</span>
                </div>
                <div class="space-y-2">
                    <?php if($user->payoutDetail->provider_name): ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700">Bank/E-Wallet:</span>
                        <span class="font-medium text-blue-900"><?php echo e($user->payoutDetail->provider_name); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($user->payoutDetail->account_number): ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700">No. Rekening:</span>
                        <span class="font-medium text-blue-900 font-mono"><?php echo e($user->payoutDetail->account_number); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if($user->payoutDetail->account_holder_name): ?>
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700">Atas Nama:</span>
                        <span class="font-medium text-blue-900"><?php echo e($user->payoutDetail->account_holder_name); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if($user->documents->count() > 0): ?>
            <div class="mt-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center mb-3">
                    <i class="fas fa-file-alt text-gray-600 text-lg mr-2"></i>
                    <span class="text-gray-800 font-semibold">Dokumen Pendukung</span>
                </div>
                <div class="space-y-2">
                    <?php $__currentLoopData = $user->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-2 bg-white rounded border border-gray-200">
                        <div class="flex items-center overflow-hidden">
                            <i class="fas fa-file-pdf text-red-500 mr-2 flex-shrink-0"></i>
                            <div class="truncate">
                                <p class="text-sm font-medium text-gray-700 truncate" title="<?php echo e($doc->original_name ?? $doc->document_type); ?>">
                                    <?php echo e($doc->document_type); ?>

                                </p>
                                <p class="text-xs text-gray-500 truncate"><?php echo e($doc->original_name); ?></p>
                            </div>
                        </div>
                        <a href="<?php echo e(asset('storage/' . $doc->file_path)); ?>" target="_blank" class="text-teal-600 hover:text-teal-800 ml-2 flex-shrink-0" title="Lihat Dokumen">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if($user->role == 'customer'): ?>
            <div class="mt-6 bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-yellow-800 font-medium">Loyalty Points</span>
                    <i class="fas fa-star text-yellow-500 text-xl"></i>
                </div>
                <p class="text-3xl font-bold text-yellow-600"><?php echo e($user->loyalty_points); ?></p>
                <p class="text-xs text-yellow-700 mt-1">Dapat ditukar dengan voucher diskon</p>
            </div>
            <?php endif; ?>

            <div class="mt-6 flex gap-2">
                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="flex-1 bg-blue-600 text-white py-2 rounded-lg text-center hover:bg-blue-700 transition">
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column: Stats & History -->
    <div class="lg:col-span-2 space-y-6">
        <?php if($user->role == 'customer'): ?>
        <!-- Booking History -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Riwayat Booking</h3>
                <span class="bg-teal-100 text-teal-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    <?php echo e($user->bookings->count()); ?> Total
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Layanan</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $user->bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-mono text-gray-600">
                                <?php echo e($booking->booking_number); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <?php echo e(optional($booking->service)->name ?? 'Service Deleted'); ?>

                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?php echo e(\Carbon\Carbon::parse($booking->scheduled_date)->format('d/m/Y')); ?>

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
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                Belum ada riwayat booking
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Loyalty History -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Riwayat Poin Loyalitas</h3>
            </div>
            <div class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $user->loyaltyTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                    <div>
                        <p class="text-sm font-medium text-gray-800"><?php echo e($transaction->description); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($transaction->created_at->format('d M Y H:i')); ?></p>
                    </div>
                    <span class="font-bold <?php echo e($transaction->points > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                        <?php echo e($transaction->points > 0 ? '+' : ''); ?><?php echo e($transaction->points); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="px-6 py-8 text-center text-gray-500">
                    Belum ada riwayat poin
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <!-- Staff Handling History -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Riwayat Penanganan Pasien</h3>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    <?php echo e($user->bookingsAsPetugas->count()); ?> Pasien
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Pasien</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Layanan</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Jadwal</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $user->bookingsAsPetugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900"><?php echo e(optional($booking->customer)->name ?? 'Unknown Customer'); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($booking->booking_number); ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <div class="font-medium"><?php echo e(optional($booking->service)->name ?? 'Service Deleted'); ?></div>
                                <?php if($booking->packagePurchase): ?>
                                    <div class="text-xs text-teal-600 mt-1">
                                        <i class="fas fa-box mr-1"></i>
                                        Paket: <?php echo e($booking->packagePurchase->package_name); ?> 
                                        (<?php echo e($booking->packagePurchase->total_sessions); ?>x)
                                        - Kunjungan ke-<?php echo e($booking->visit_number); ?>

                                    </div>
                                <?php endif; ?>
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
                            <td class="px-6 py-4">
                                <a href="<?php echo e(route('admin.bookings.show', $booking)); ?>" class="text-teal-600 hover:text-teal-900 text-sm font-medium">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Belum ada riwayat penanganan pasien
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/users/show.blade.php ENDPATH**/ ?>