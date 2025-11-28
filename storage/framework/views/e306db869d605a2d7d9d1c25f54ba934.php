

<?php $__env->startSection('title', 'Detail Pembayaran - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Detail Pembayaran #<?php echo e($payment->payment_number); ?></h1>
        <a href="<?php echo e(route('admin.payments.index')); ?>" class="text-teal-600 hover:text-teal-800 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Payment Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h2>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Customer</label>
                    <p class="font-medium text-gray-900"><?php echo e($payment->booking->customer->name ?? '-'); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e($payment->booking->customer->email ?? '-'); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Booking</label>
                    <p class="font-medium text-gray-900"><?php echo e($payment->booking->booking_number ?? '-'); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e($payment->booking->service->name ?? '-'); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Tipe Pembayaran</label>
                    <?php if($payment->payment_type == 'dp'): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">DP Paket</span>
                    <?php elseif($payment->payment_type == 'full'): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Bayar Paket Penuh</span>
                    <?php elseif($payment->payment_type == 'addon'): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">Pembayaran Add-ons</span>
                    <?php else: ?>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><?php echo e(ucfirst($payment->payment_type)); ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Jumlah</label>
                    <p class="font-medium text-gray-900 text-lg">Rp <?php echo e(number_format($payment->total_amount, 0, ',', '.')); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Metode Pembayaran</label>
                    <p class="font-medium text-gray-900"><?php echo e(ucfirst($payment->payment_method)); ?></p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Status</label>
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
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Tanggal</label>
                    <p class="font-medium text-gray-900"><?php echo e($payment->created_at->format('d M Y H:i')); ?></p>
                </div>
            </div>
        </div>

        
        <?php if($payment->payment_type == 'addon' && $payment->booking->bookingAddons->count() > 0): ?>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Add-ons yang Dibayar</h2>
            <div class="space-y-2">
                <?php $__currentLoopData = $payment->booking->bookingAddons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bookingAddon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900"><?php echo e($bookingAddon->addon->name); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($bookingAddon->quantity); ?> x Rp <?php echo e(number_format($bookingAddon->price_per_item, 0, ',', '.')); ?></p>
                    </div>
                    <p class="font-semibold text-teal-600">Rp <?php echo e(number_format($bookingAddon->subtotal, 0, ',', '.')); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                    <p class="font-bold text-gray-900">Total Add-ons:</p>
                    <p class="font-bold text-teal-600 text-lg">Rp <?php echo e(number_format($payment->booking->bookingAddons->sum('subtotal'), 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Proof & Actions -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Bukti Pembayaran</h3>
            <?php if($payment->payment_proof): ?>
                <div class="flex flex-col items-center">
                    <img src="<?php echo e(asset('storage/' . $payment->payment_proof)); ?>" alt="Bukti Pembayaran" class="max-w-xs w-full rounded-lg border border-gray-200 mb-3 cursor-pointer hover:opacity-90 transition" onclick="openImageModal('<?php echo e(asset('storage/' . $payment->payment_proof)); ?>')">
                    <button onclick="openImageModal('<?php echo e(asset('storage/' . $payment->payment_proof)); ?>')" class="text-sm text-blue-600 hover:underline">
                        <i class="fas fa-search-plus mr-1"></i> Lihat Ukuran Penuh
                    </button>
                </div>
            <?php else: ?>
                <div class="text-center py-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                    <p class="text-sm text-gray-500">Belum ada bukti pembayaran</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if($payment->status == 'pending_verification' || $payment->status == 'pending'): ?>
        <div class="bg-white rounded-xl shadow-sm p-6" x-data="{ showVerifyModal: false, showRejectForm: false }">
            <h3 class="font-semibold text-gray-800 mb-4">Aksi</h3>
            <div class="space-y-3">
                <button type="button" @click="showVerifyModal = true" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium">
                    <i class="fas fa-check-circle mr-2"></i> Verifikasi Pembayaran
                </button>
                
                <button type="button" @click="showRejectForm = !showRejectForm" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium">
                    <i class="fas fa-times-circle mr-2"></i> Tolak Pembayaran
                </button>

                <div x-show="showRejectForm" x-transition class="mt-4 pt-4 border-t border-gray-100">
                    <form action="<?php echo e(route('admin.payments.reject', $payment)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan</label>
                            <textarea name="rejection_reason" rows="2" required class="w-full px-3 py-2 border rounded-lg text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-red-700 text-white px-3 py-1.5 rounded text-sm hover:bg-red-800">
                            Konfirmasi Penolakan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Verify Confirmation Modal -->
            <div x-show="showVerifyModal" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
                 @click="showVerifyModal = false">
                <div @click.stop class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-90">
                    <div class="text-center mb-4">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-3">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Verifikasi Pembayaran</h3>
                        <p class="text-sm text-gray-600">Apakah Anda yakin ingin memverifikasi pembayaran ini?</p>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" @click="showVerifyModal = false" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-medium">
                            Batal
                        </button>
                        <form action="<?php echo e(route('admin.payments.verify', $payment)); ?>" method="POST" class="flex-1">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium">
                                Ya, Verifikasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Image Modal for Payment Proof with Zoom -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative w-full h-full flex items-center justify-center" onclick="event.stopPropagation()">
        <!-- Close Button -->
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 text-3xl z-10 bg-black bg-opacity-50 w-12 h-12 rounded-full">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Zoom Controls -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
            <button onclick="zoomOut()" class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg hover:bg-opacity-70">
                <i class="fas fa-search-minus"></i>
            </button>
            <button onclick="resetZoom()" class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg hover:bg-opacity-70">
                <i class="fas fa-compress"></i> Reset
            </button>
            <button onclick="zoomIn()" class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg hover:bg-opacity-70">
                <i class="fas fa-search-plus"></i>
            </button>
        </div>
        
        <!-- Image Container -->
        <div class="overflow-auto max-w-full max-h-full" id="imageContainer">
            <img id="modalImage" src="" alt="Bukti Pembayaran" class="transition-transform duration-200" style="cursor: grab;">
        </div>
    </div>
</div>

<script>
    // Image modal with zoom functionality
    let currentZoom = 1;
    let isDragging = false;
    let startX, startY, scrollLeft, scrollTop;

    function openImageModal(imageUrl) {
        const modal = document.getElementById('imageModal');
        const img = document.getElementById('modalImage');
        img.src = imageUrl;
        modal.classList.remove('hidden');
        resetZoom();
        
        // Add wheel zoom
        const container = document.getElementById('imageContainer');
        container.addEventListener('wheel', handleWheel, { passive: false });
        
        // Add drag to pan
        img.addEventListener('mousedown', startDrag);
        img.addEventListener('mousemove', drag);
        img.addEventListener('mouseup', stopDrag);
        img.addEventListener('mouseleave', stopDrag);
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        currentZoom = 1;
    }

    function zoomIn() {
        currentZoom = Math.min(currentZoom + 0.25, 5);
        applyZoom();
    }

    function zoomOut() {
        currentZoom = Math.max(currentZoom - 0.25, 0.5);
        applyZoom();
    }

    function resetZoom() {
        currentZoom = 1;
        applyZoom();
    }

    function applyZoom() {
        const img = document.getElementById('modalImage');
        img.style.transform = `scale(${currentZoom})`;
    }

    function handleWheel(e) {
        e.preventDefault();
        if (e.deltaY < 0) {
            zoomIn();
        } else {
            zoomOut();
        }
    }

    function startDrag(e) {
        if (currentZoom > 1) {
            isDragging = true;
            const container = document.getElementById('imageContainer');
            startX = e.pageX - container.offsetLeft;
            startY = e.pageY - container.offsetTop;
            scrollLeft = container.scrollLeft;
            scrollTop = container.scrollTop;
            e.target.style.cursor = 'grabbing';
        }
    }

    function drag(e) {
        if (!isDragging) return;
        e.preventDefault();
        const container = document.getElementById('imageContainer');
        const x = e.pageX - container.offsetLeft;
        const y = e.pageY - container.offsetTop;
        const walkX = (x - startX) * 2;
        const walkY = (y - startY) * 2;
        container.scrollLeft = scrollLeft - walkX;
        container.scrollTop = scrollTop - walkY;
    }

    function stopDrag(e) {
        isDragging = false;
        e.target.style.cursor = 'grab';
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/payments/show.blade.php ENDPATH**/ ?>