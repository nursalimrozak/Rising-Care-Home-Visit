

<?php $__env->startSection('title', 'Detail Customer - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Detail Customer</h1>
        <button onclick="history.back()" class="text-teal-600 hover:text-teal-800 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Customer Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Customer</h2>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Nama Lengkap</label>
                    <p class="font-medium text-gray-900"><?php echo e($user->name); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Email</label>
                    <p class="text-gray-900"><?php echo e($user->email); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">No. Telepon</label>
                    <p class="text-gray-900"><?php echo e($user->phone ?? '-'); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Tanggal Lahir / Umur</label>
                    <p class="text-gray-900">
                        <?php echo e($user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d F Y') : '-'); ?>

                        <?php if($user->date_of_birth): ?>
                            <span class="text-gray-500 text-sm">(<?php echo e(\Carbon\Carbon::parse($user->date_of_birth)->age); ?> tahun)</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Nomor BPJS</label>
                    <p class="text-gray-900"><?php echo e($user->bpjs_number ?? '-'); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Jenis Kelamin</label>
                    <p class="text-gray-900"><?php echo e($user->gender == 'male' ? 'Laki-laki' : ($user->gender == 'female' ? 'Perempuan' : '-')); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Pekerjaan</label>
                    <p class="text-gray-900"><?php echo e($user->occupation->name ?? '-'); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Agama</label>
                    <p class="text-gray-900"><?php echo e($user->religion ?? '-'); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Status Pernikahan</label>
                    <p class="text-gray-900"><?php echo e($user->marital_status ?? '-'); ?></p>
                </div>
            </div>

            <?php if($user->address): ?>
            <div class="mt-6 pt-6 border-t">
                <label class="text-sm text-gray-500 block mb-1">Alamat</label>
                <p class="text-gray-900"><?php echo e($user->address); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Emergency Contact -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Kontak Darurat</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Nama Lengkap</label>
                    <p class="font-medium text-gray-900"><?php echo e($user->emergency_contact_name ?? '-'); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Hubungan</label>
                    <p class="text-gray-900"><?php echo e($user->emergency_contact_relationship ?? '-'); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">No. Telepon</label>
                    <p class="text-gray-900"><?php echo e($user->emergency_contact_phone ?? '-'); ?></p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm text-gray-500 block mb-1">Alamat</label>
                    <p class="text-gray-900"><?php echo e($user->emergency_contact_address ?? '-'); ?></p>
                </div>
            </div>
        </div>

        <!-- Health Records -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Rekap Kesehatan</h2>
            
            <?php if($user->healthRecords->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $user->healthRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border-l-4 border-teal-500 bg-gray-50 p-4 rounded">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-semibold text-gray-900"><?php echo e($record->category->name); ?></h3>
                                <p class="text-sm text-gray-500"><?php echo e(\Carbon\Carbon::parse($record->recorded_at)->format('d M Y H:i')); ?></p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <?php $__currentLoopData = $record->answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <p class="text-xs text-gray-500"><?php echo e($answer['question']); ?></p>
                                <p class="font-medium text-gray-900"><?php echo e($answer['answer']); ?></p>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <?php if($record->notes): ?>
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-sm text-gray-600"><strong>Catatan:</strong> <?php echo e($record->notes); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-center py-8">Belum ada rekap kesehatan</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar Stats -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Statistik</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Booking</span>
                    <span class="font-bold text-gray-900"><?php echo e($user->bookingsAsCustomer->count()); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Booking Selesai</span>
                    <span class="font-bold text-green-600"><?php echo e($user->bookingsAsCustomer->where('status', 'completed')->count()); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Rekap Kesehatan</span>
                    <span class="font-bold text-teal-600"><?php echo e($user->healthRecords->count()); ?></span>
                </div>
            </div>
        </div>

        <?php if($user->membership): ?>
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-sm p-6 text-white">
            <h3 class="font-semibold mb-2">Membership</h3>
            <p class="text-2xl font-bold"><?php echo e($user->membership->name); ?></p>
            <p class="text-sm opacity-90 mt-1">Diskon <?php echo e($user->membership->discount_percentage); ?>%</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.petugas', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/petugas/customers/show.blade.php ENDPATH**/ ?>