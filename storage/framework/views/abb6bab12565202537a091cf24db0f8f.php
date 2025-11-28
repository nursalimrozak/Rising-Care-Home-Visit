

<?php $__env->startSection('title', 'Buat Janji - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="<?php echo e(Auth::check() && Auth::user()->role == 'customer' ? '' : 'min-h-screen bg-gray-50 py-12'); ?>">
    <div class="<?php echo e(Auth::check() && Auth::user()->role == 'customer' ? 'max-w-7xl mx-auto' : 'container mx-auto px-4'); ?>">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-teal-600 py-6 px-8 text-white">
                <h1 class="text-3xl font-bold">Buat Janji Temu</h1>
                <p class="mt-2 text-teal-100">Isi formulir di bawah ini untuk menjadwalkan layanan kesehatan Anda.</p>
            </div>

            <form action="<?php echo e(route('booking.store')); ?>" method="POST" class="p-8">
                <?php echo csrf_field(); ?>

                <?php if(session('error') && session('show_modal')): ?>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Paket Aktif Ditemukan',
                            text: '<?php echo e(session('error')); ?>',
                            confirmButtonColor: '#0d9488',
                            confirmButtonText: 'Mengerti'
                        });
                    });
                </script>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Terdapat beberapa kesalahan pada input Anda. Mohon periksa kembali.
                                </p>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php if($packagePurchase): ?>
                <div class="bg-gradient-to-r from-teal-50 to-green-50 border-2 border-teal-300 rounded-xl p-6 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-box-open text-teal-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 text-lg mb-2">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                Menggunakan Paket: <?php echo e($packagePurchase->package->name); ?>

                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">Layanan</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($packagePurchase->service->name); ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Sisa Kunjungan</p>
                                    <p class="font-semibold text-teal-600"><?php echo e($packagePurchase->getRemainingVisits()); ?>x</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Total Kunjungan</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($packagePurchase->total_visits); ?>x</p>
                                </div>
                                <?php if($packagePurchase->expires_at): ?>
                                <div>
                                    <p class="text-gray-600">Berlaku Hingga</p>
                                    <p class="font-semibold <?php echo e($packagePurchase->isExpired() ? 'text-red-600' : 'text-gray-900'); ?>">
                                        <?php echo e($packagePurchase->expires_at->format('d M Y')); ?>

                                    </p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php if($lastBookingDate): ?>
                            <div class="mt-3 pt-3 border-t border-teal-200">
                                <p class="text-xs text-gray-600">
                                    <i class="fas fa-info-circle text-teal-600 mr-1"></i>
                                    Booking terakhir: <strong><?php echo e(\Carbon\Carbon::parse($lastBookingDate)->format('d M Y')); ?></strong>. 
                                    Jadwal berikutnya harus setelah tanggal tersebut.
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                
                <input type="hidden" name="use_existing_package" value="<?php echo e($packagePurchase->id); ?>">
                <?php endif; ?>

                <div class="space-y-6">
                    <!-- Bagian Kiri: Detail Layanan -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Detail Layanan</h2>
                        
                        <!-- Hidden service field - will be set based on package selection -->
                        <input type="hidden" name="service_id" id="service_id" value="<?php echo e($packagePurchase->service_id ?? old('service_id', $services->first()->id ?? '')); ?>">
                        
                        <?php if(!$packagePurchase): ?>
                        <div class="mb-6">
                            <label class="block text-gray-700 font-medium mb-3">Pilih Paket <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="package-container">
                                <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="relative cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="package_id" 
                                        value="<?php echo e($package->id); ?>" 
                                        required 
                                        class="peer sr-only"
                                        <?php echo e(old('package_id') == $package->id ? 'checked' : ''); ?>

                                        data-package-id="<?php echo e($package->id); ?>"
                                        data-visit-count="<?php echo e($package->visit_count); ?>"
                                    >
                                    <div class="h-full p-5 border-2 rounded-xl transition-all peer-checked:border-teal-500 peer-checked:bg-teal-50 peer-checked:shadow-lg border-gray-200 hover:border-teal-300">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h3 class="font-bold text-gray-900 text-xl"><?php echo e($package->name); ?></h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <?php echo e($package->visit_count); ?>x kunjungan
                                                    <?php if($package->validity_weeks > 0): ?>
                                                        <span class="text-xs text-gray-500">(<?php echo e($package->validity_weeks); ?> minggu)</span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                            <div class="peer-checked:block hidden">
                                                <i class="fas fa-check-circle text-teal-600 text-2xl"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <div class="flex items-baseline gap-2">
                                                <span class="text-2xl font-bold text-teal-600" data-price-display="<?php echo e($package->id); ?>">
                                                    Rp 0
                                                </span>
                                            </div>
                                            <?php if($package->visit_count > 1): ?>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-tag mr-1"></i>
                                                    <span data-per-visit-display="<?php echo e($package->id); ?>">Rp 0/kunjungan</span>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <p class="text-xs text-gray-500 mt-3">
                                <i class="fas fa-info-circle"></i> Harga disesuaikan dengan membership <strong><?php echo e(Auth::user()->membership->name ?? 'Anda'); ?></strong>
                            </p>
                        </div>
                        <?php else: ?>
                        
                        <input type="hidden" name="package_id" value="<?php echo e($packagePurchase->package_id); ?>">
                        <?php endif; ?>

                        <!-- Payment Information -->
                        <?php if(!$packagePurchase): ?>
                        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg" id="payment-info" style="display: none;">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-blue-900 mb-2">Informasi Pembayaran</h4>
                                    <div id="payment-amount-info">
                                        <!-- Will be filled by JavaScript -->
                                    </div>
                                    <p class="text-sm text-blue-700 mt-2">
                                        <i class="fas fa-arrow-right mr-1"></i> Upload bukti transfer setelah booking dibuat
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        
                        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-green-900 mb-2">Tidak Ada Pembayaran Tambahan</h4>
                                    <p class="text-sm text-green-700">
                                        Paket sudah dibayar. Anda hanya perlu menjadwalkan kunjungan berikutnya.
                                    </p>
                                    <p class="text-xs text-green-600 mt-1">
                                        <i class="fas fa-info-circle mr-1"></i> Pembayaran add-ons (jika ada) dilakukan setelah treatment selesai.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-medium mb-2">Tipe Layanan <span class="text-red-500">*</span></label>
                            <div class="flex gap-4">
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 w-full transition bg-teal-50 border-teal-500">
                                    <input type="radio" name="booking_type" required value="home_visit" class="text-teal-600 focus:ring-teal-500" checked onclick="toggleAddress(true)">
                                    <div class="ml-3">
                                        <span class="block font-medium text-gray-900">Home Visit</span>
                                        <span class="block text-sm text-gray-500">Layanan di rumah Anda</span>
                                    </div>
                                </label>
                                <label class="flex items-center p-4 border rounded-lg w-full transition bg-gray-100 border-gray-300 cursor-not-allowed opacity-60">
                                    <input type="radio" name="booking_type" value="on_site" class="text-gray-400" disabled>
                                    <div class="ml-3">
                                        <span class="block font-medium text-gray-500">Klinik</span>
                                        <span class="block text-xs text-gray-400">
                                            <i class="fas fa-clock mr-1"></i>Coming Soon
                                        </span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Tanggal <span class="text-red-500">*</span></label>
                                <input type="date" name="scheduled_date" value="<?php echo e(old('scheduled_date')); ?>" min="<?php echo e($minDate); ?>" <?php echo e($maxDate ? 'max='.$maxDate : ''); ?> required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                <?php if($packagePurchase): ?>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-info-circle text-teal-600"></i> 
                                    <?php if($lastBookingDate): ?>
                                        Minimal: <?php echo e(\Carbon\Carbon::parse($minDate)->format('d M Y')); ?>

                                    <?php endif; ?>
                                    <?php if($maxDate): ?>
                                        <?php if($lastBookingDate): ?> | <?php endif; ?>
                                        Maksimal: <?php echo e(\Carbon\Carbon::parse($maxDate)->format('d M Y')); ?>

                                    <?php endif; ?>
                                </p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Jam <span class="text-red-500">*</span></label>
                                <input type="time" name="scheduled_time" value="<?php echo e(old('scheduled_time')); ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                        </div>

                        <div id="address-field" class="mb-6">
                            <label class="block text-gray-700 font-medium mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="customer_address" id="customer_address" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Masukkan alamat lengkap untuk kunjungan rumah"><?php echo e(old('customer_address')); ?></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-medium mb-2">Catatan Tambahan (Opsional)</label>
                            <textarea name="customer_notes" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Keluhan atau catatan khusus..."><?php echo e(old('customer_notes')); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t">
                    <button type="submit" class="w-full bg-teal-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-teal-700 transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-calendar-check"></i> Konfirmasi Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Package pricing data (from backend)
    const packagePrices = <?php echo json_encode($services->first()->packagePrices->groupBy('package_id')->map(function($prices) {
        return $prices->keyBy('membership_id')->map(function($price) {
            return $price->price;
        });
    }), 15, 512) ?>;
    
    const userMembershipId = <?php echo e(Auth::user()->membership_id ?? 0); ?>;
    
    // Update prices on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateAllPrices();
    });
    
    function updateAllPrices() {
        document.querySelectorAll('input[name="package_id"]').forEach(function(radio) {
            const packageId = radio.dataset.packageId;
            const visitCount = parseInt(radio.dataset.visitCount);
            
            if (packagePrices[packageId] && packagePrices[packageId][userMembershipId]) {
                const price = packagePrices[packageId][userMembershipId];
                const priceDisplay = document.querySelector(`[data-price-display="${packageId}"]`);
                const perVisitDisplay = document.querySelector(`[data-per-visit-display="${packageId}"]`);
                
                if (priceDisplay) {
                    priceDisplay.textContent = 'Rp ' + formatNumber(price);
                }
                
                if (perVisitDisplay && visitCount > 1) {
                    const perVisit = price / visitCount;
                    perVisitDisplay.textContent = 'Rp ' + formatNumber(perVisit) + '/kunjungan';
                }
            }
        });
    }
    
    function formatNumber(num) {
        return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Show payment info when package is selected
    document.addEventListener('DOMContentLoaded', function() {
        const packageRadios = document.querySelectorAll('input[name="package_id"]');
        const paymentInfo = document.getElementById('payment-info');
        const paymentAmountInfo = document.getElementById('payment-amount-info');
        
        packageRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const visitCount = parseInt(this.dataset.visitCount);
                const packageId = this.dataset.packageId;
                const price = packagePrices[packageId] && packagePrices[packageId][userMembershipId] 
                    ? packagePrices[packageId][userMembershipId] 
                    : 0;
                
                if (visitCount === 1) {
                    // Reguler package - DP Rp 100.000
                    paymentAmountInfo.innerHTML = `
                        <p class="text-sm text-blue-800">
                            <strong>DP yang harus dibayar:</strong> <span class="text-lg font-bold">Rp 100.000</span>
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            Sisa pembayaran: Rp ${formatNumber(price - 100000)} (dibayar setelah terapi)
                        </p>
                    `;
                } else {
                    // Multi-visit package - Full payment
                    paymentAmountInfo.innerHTML = `
                        <p class="text-sm text-blue-800">
                            <strong>Total yang harus dibayar:</strong> <span class="text-lg font-bold">Rp ${formatNumber(price)}</span>
                        </p>
                        <p class="text-xs text-blue-600 mt-1">
                            Full payment untuk ${visitCount}x kunjungan
                        </p>
                    `;
                }
                
                paymentInfo.style.display = 'block';
            });
        });
    });
    
    function toggleAddress(show) {
        const addressField = document.getElementById('address-field');
        const addressInput = document.getElementById('customer_address');
        
        if (show) {
            addressField.classList.remove('hidden');
            addressInput.required = true;
        } else {
            addressField.classList.add('hidden');
            addressInput.required = false;
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(Auth::check() && Auth::user()->role == 'customer' ? 'layouts.customer' : 'layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/booking/create.blade.php ENDPATH**/ ?>