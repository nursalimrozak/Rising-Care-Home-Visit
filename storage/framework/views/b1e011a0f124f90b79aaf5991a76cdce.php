

<?php $__env->startSection('title', 'About Section - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">About Section</h1>
    <p class="text-gray-600">Kelola konten bagian "About Us" di landing page</p>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <form action="<?php echo e(route('admin.landing.about.update')); ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column: Content -->
            <div class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Section Title</label>
                    <input type="text" name="settings[landing_about_title]" value="<?php echo e($settings['landing_about_title'] ?? 'Empower Your Life With Healthcare Care'); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea name="settings[landing_about_description]" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"><?php echo e($settings['landing_about_description'] ?? 'RisingCare menyediakan layanan kesehatan berkualitas dengan tenaga medis profesional dan berpengalaman. Kami berkomitmen untuk memberikan perawatan terbaik untuk Anda dan keluarga.'); ?></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Key Points</label>
                    <div id="points-container" class="space-y-3">
                        <?php $__currentLoopData = $points; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex gap-2">
                            <input type="text" name="points[]" value="<?php echo e($point); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 px-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <button type="button" onclick="addPoint()" class="mt-3 text-sm text-teal-600 font-medium hover:text-teal-800">
                        <i class="fas fa-plus mr-1"></i> Tambah Point
                    </button>
                </div>
            </div>

            <!-- Right Column: Image -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Section Image</label>
                <div class="bg-gray-50 rounded-xl p-4 border-2 border-dashed border-gray-300 text-center">
                    <?php if(isset($settings['landing_about_image'])): ?>
                        <img src="<?php echo e(asset('storage/' . $settings['landing_about_image'])); ?>" alt="About Image" class="max-h-64 mx-auto rounded-lg mb-4">
                    <?php else: ?>
                        <div class="h-64 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" name="settings[landing_about_image]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 transition-colors">
                    <p class="text-xs text-gray-500 mt-2">Recommended size: 600x400px</p>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-end">
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
function addPoint() {
    const container = document.getElementById('points-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="points[]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
        <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 px-2">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(div);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/landing/about/index.blade.php ENDPATH**/ ?>