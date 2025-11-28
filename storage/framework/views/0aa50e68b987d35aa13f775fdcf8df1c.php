

<?php $__env->startSection('title', 'CTA Appointment Section - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">CTA Appointment Section</h1>
    <p class="text-gray-600">Kelola section Call-to-Action di landing page</p>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="<?php echo e(route('admin.landing.cta-appointment.update')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-medium mb-2">Section Title <span class="text-red-500">*</span></label>
                <input type="text" name="section_title" value="<?php echo e(old('section_title', $cta->section_title)); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <?php $__errorArgs = ['section_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-medium mb-2">Section Subtitle</label>
                <textarea name="section_subtitle" rows="2"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"><?php echo e(old('section_subtitle', $cta->section_subtitle)); ?></textarea>
                <?php $__errorArgs = ['section_subtitle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Button Text <span class="text-red-500">*</span></label>
                <input type="text" name="button_text" value="<?php echo e(old('button_text', $cta->button_text)); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <?php $__errorArgs = ['button_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Background Color</label>
                <input type="color" name="background_color" value="<?php echo e(old('background_color', $cta->background_color ?? '#0d9488')); ?>"
                    class="w-full h-10 px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Warna background section</p>
                <?php $__errorArgs = ['background_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-medium mb-2">Background Image (Opsional)</label>
                <?php if($cta->background_image): ?>
                    <div class="mb-2">
                        <img src="<?php echo e(asset('storage/' . $cta->background_image)); ?>" alt="Background" class="w-full h-32 object-cover rounded-lg">
                    </div>
                <?php endif; ?>
                <input type="file" name="background_image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Upload gambar background (akan override warna background)</p>
                <?php $__errorArgs = ['background_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', $cta->is_active) ? 'checked' : ''); ?>

                        class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                    <span class="ml-2 text-gray-700">Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
            <button type="submit" class="bg-teal-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-teal-700 transition shadow-lg">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/landing/cta-appointment/edit.blade.php ENDPATH**/ ?>