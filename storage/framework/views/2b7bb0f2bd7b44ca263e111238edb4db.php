

<?php $__env->startSection('title', 'Detail Treatment - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6" x-data="{ showProofModal: false, proofImage: '' }">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Detail Treatment</h1>
        <a href="<?php echo e(route('customer.packages.show', $booking->packagePurchase->id)); ?>" class="text-teal-600 hover:text-teal-800 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Paket
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Treatment Info Card -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <?php
                            // Calculate dynamic visit number based on date order to handle legacy data
                            $visitIndex = $booking->packagePurchase->bookings()
                                ->orderBy('scheduled_date')
                                ->pluck('id')
                                ->search($booking->id) + 1;
                        ?>
                        <h2 class="text-xl font-bold text-gray-800">Kunjungan #<?php echo e($visitIndex); ?></h2>
                        <p class="text-gray-600 mt-1"><?php echo e($booking->packagePurchase->package->name); ?></p>
                    </div>
                    <?php
                        $statusClasses = [
                            'scheduled' => 'bg-yellow-100 text-yellow-800',
                            'checked_in' => 'bg-blue-100 text-blue-800',
                            'in_progress' => 'bg-purple-100 text-purple-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'scheduled' => 'Terjadwal',
                            'checked_in' => 'Check-in',
                            'in_progress' => 'Diproses',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];
                    ?>
                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo e($statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                        <?php echo e($statusLabels[$booking->status] ?? ucfirst($booking->status)); ?>

                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                        <p class="font-medium text-gray-900">
                            <?php echo e(\Carbon\Carbon::parse($booking->scheduled_date)->format('d M Y')); ?><br>
                            <?php echo e(\Carbon\Carbon::parse($booking->scheduled_time)->format('H:i')); ?> WIB
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Petugas</p>
                        <p class="font-medium text-gray-900">
                            <?php if($booking->petugas): ?>
                                <?php echo e($booking->petugas->name); ?>

                            <?php else: ?>
                                <?php
                                    // Find assigned staff from any booking in this package
                                    $assignedStaff = $booking->packagePurchase->bookings->whereNotNull('petugas_id')->first()->petugas ?? null;
                                ?>
                                <?php if($assignedStaff): ?>
                                    <?php echo e($assignedStaff->name); ?> <span class="text-teal-600">(Terjadwal)</span>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">Belum ditugaskan</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tipe Layanan</p>
                        <p class="font-medium text-gray-900">
                            <?php echo e($booking->booking_type == 'home_visit' ? 'Home Visit' : 'Klinik'); ?>

                        </p>
                    </div>
                    <?php if($booking->booking_type == 'home_visit' && $booking->address): ?>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-medium text-gray-900"><?php echo e($booking->address); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Package Payment Info -->
            <!-- Package Payment Info -->
            <?php if($booking->packagePurchase->status == 'pending'): ?>
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 mb-1">Menunggu Pembayaran Paket</h3>
                        <p class="text-sm text-gray-700">
                            Silakan selesaikan pembayaran paket <strong><?php echo e($booking->packagePurchase->package->name); ?></strong>.
                            Kunjungan pertama akan dijadwalkan setelah pembayaran diverifikasi.
                        </p>
                        <?php if($booking->payment): ?>
                        <p class="text-sm text-gray-600 mt-2 font-medium">
                            Total Tagihan: Rp <?php echo e(number_format($booking->payment->total_amount, 0, ',', '.')); ?>

                        </p>
                        <?php endif; ?>
                    </div>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">MENUNGGU</span>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-gradient-to-r from-green-50 to-teal-50 border border-green-200 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 mb-1">Paket Sudah Dibayar</h3>
                        <p class="text-sm text-gray-700">
                            Pembayaran paket <strong><?php echo e($booking->packagePurchase->package->name); ?></strong> sudah lunas. 
                            Biaya treatment ini sudah termasuk dalam paket Anda.
                        </p>
                        <p class="text-sm text-gray-600 mt-2 italic">
                            <i class="fas fa-info-circle mr-1"></i>
                            Belum termasuk biaya add-ons (jika ada yang digunakan saat treatment).
                        </p>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">LUNAS</span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Add-ons Section -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Add-ons Treatment</h3>
                <?php if($booking->bookingAddons && $booking->bookingAddons->count() > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $booking->bookingAddons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900"><?php echo e($addon->addon->name); ?></p>
                                <p class="text-sm text-gray-600"><?php echo e($addon->quantity); ?> x Rp <?php echo e(number_format($addon->price, 0, ',', '.')); ?></p>
                            </div>
                            <p class="font-semibold text-gray-900">Rp <?php echo e(number_format($addon->subtotal, 0, ',', '.')); ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <div class="border-t pt-3 flex justify-between font-bold text-lg">
                            <span>Total Add-ons</span>
                            <span class="text-teal-600">Rp <?php echo e(number_format($addonsTotal, 0, ',', '.')); ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-box-open text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Belum ada add-ons yang digunakan pada treatment ini</p>
                        <p class="text-sm text-gray-400 mt-1">Add-ons akan ditambahkan oleh petugas saat treatment berlangsung</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Payment Info (only if there are add-ons) -->
            <?php if($addonsTotal > 0): ?>
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-2">Informasi Pembayaran Add-ons</h4>
                        <p class="text-sm text-blue-800">
                            Pembayaran add-ons dilakukan terpisah dari paket. Silakan lakukan pembayaran add-ons 
                            setelah treatment selesai melalui transfer bank ke rekening yang tertera di samping.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar (Always visible) -->
        <div class="space-y-6">
            <?php if($remainingAmount > 0): ?>
            <!-- Bank Transfer Info -->
            <?php if($bankAccounts->count() > 0): ?>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-university mr-2 text-teal-600"></i> Transfer Rekening
                </h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $bankAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-bold text-gray-900"><?php echo e($account->bank_name); ?></p>
                                <p class="text-sm text-gray-600 font-mono"><?php echo e($account->account_number); ?></p>
                                <p class="text-xs text-gray-500">a.n. <?php echo e($account->account_holder); ?></p>
                            </div>
                            <button onclick="copyToClipboard('<?php echo e($account->account_number); ?>')" 
                                class="px-3 py-1.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-xs font-medium">
                                <i class="fas fa-copy mr-1"></i> Salin
                            </button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <p class="text-xs text-teal-700 mt-3 bg-teal-50 p-2 rounded">
                    <i class="fas fa-info-circle mr-1"></i> Transfer sejumlah 
                    <strong>Rp <?php echo e(number_format($remainingAmount, 0, ',', '.')); ?></strong>
                </p>
            </div>
            <?php endif; ?>

            <!-- Upload Payment Proof -->
            <?php if($remainingAmount > 0 && $booking->status == 'completed'): ?>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Upload Bukti Transfer</h3>
                <form action="<?php echo e(route('booking.upload-payment', $booking->booking_number)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <input type="file" name="payment_proof" accept="image/*" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                    </div>
                    <button type="submit" class="w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition font-medium">
                        <i class="fas fa-upload mr-2"></i> Upload Bukti Pembayaran
                    </button>
                </form>
            </div>
            <?php elseif($booking->payment && $booking->payment->status == 'pending_verification'): ?>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-clock text-yellow-600 mt-1"></i>
                    <div>
                        <p class="font-medium text-yellow-900">Menunggu Verifikasi Admin</p>
                        <p class="text-sm text-yellow-700 mt-1">Bukti pembayaran Anda sedang diverifikasi oleh admin.</p>
                    </div>
                </div>
            </div>
            <?php elseif($paidAmount > 0): ?>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        <span class="font-medium text-green-900">Add-ons Sudah Dibayar</span>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">LUNAS</span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Display Payment Proof -->
            <?php if($booking->payment && $booking->payment->payment_proof): ?>
            <button @click="showProofModal = true; proofImage = '<?php echo e(asset('storage/' . $booking->payment->payment_proof)); ?>'" 
                class="w-full bg-teal-50 text-teal-700 border border-teal-200 px-4 py-2 rounded-lg hover:bg-teal-100 transition font-medium flex items-center justify-center text-sm">
                <i class="fas fa-image mr-2"></i> Lihat Bukti Transfer
            </button>
            <?php endif; ?>
            <?php else: ?>
            <!-- Info card when no add-ons yet -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="text-center py-6">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-info-circle text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-gray-800 mb-2">Informasi Pembayaran</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Jika ada add-ons yang digunakan saat treatment, informasi pembayaran dan form upload bukti transfer akan ditampilkan di sini.
                    </p>
                    <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 text-left">
                        <p class="text-xs text-teal-800">
                            <i class="fas fa-lightbulb mr-1"></i>
                            <strong>Catatan:</strong> Pembayaran add-ons dilakukan setelah treatment selesai.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payment Proof Modal -->
    <div x-show="showProofModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showProofModal = false" class="relative max-w-4xl w-full max-h-full overflow-auto bg-white rounded-lg shadow-2xl">
            <button @click="showProofModal = false" class="absolute top-2 right-2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-75 transition z-10">
                <i class="fas fa-times"></i>
            </button>
            <img :src="proofImage" alt="Bukti Transfer" class="w-full h-auto object-contain">
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 text-sm flex items-center';
            toast.innerHTML = '<i class="fas fa-check-circle text-green-400 mr-2"></i> Nomor rekening disalin!';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/customer/treatment/show.blade.php ENDPATH**/ ?>