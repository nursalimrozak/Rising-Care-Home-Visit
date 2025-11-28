

<?php $__env->startSection('title', 'Tambah Layanan - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="<?php echo e(route('admin.services.index')); ?>" class="bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Layanan Baru</h1>
            <p class="text-gray-600">Isi detail layanan dan harga per membership</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-8 max-w-4xl">
    <form action="<?php echo e(route('admin.services.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Basic Info -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informasi Dasar</h3>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nama Layanan</label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                    <select name="service_category_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">Pilih Kategori</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('service_category_id') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Durasi (Menit)</label>
                    <input type="number" name="duration_minutes" value="<?php echo e(old('duration_minutes', 60)); ?>" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"><?php echo e(old('description')); ?></textarea>
                </div>
            </div>

            <!-- Pricing -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Harga & Membership</h3>
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Harga Dasar (Umum)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                        <input type="number" name="base_price" value="<?php echo e(old('base_price')); ?>" required min="0"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 font-bold text-gray-800">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Harga untuk customer tanpa membership atau default.</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Harga Khusus Membership (Opsional)</p>
                    
                    <?php $__currentLoopData = $memberships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $membership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-3">
                            <label class="block text-sm text-gray-600 mb-1"><?php echo e($membership->name); ?></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                <input type="number" name="prices[<?php echo e($membership->id); ?>]" value="<?php echo e(old('prices.'.$membership->id)); ?>" min="0"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm"
                                    placeholder="Kosongkan untuk ikut harga dasar">
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-8 pt-6 border-t">
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition">
                Simpan Layanan
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/services/create.blade.php ENDPATH**/ ?>