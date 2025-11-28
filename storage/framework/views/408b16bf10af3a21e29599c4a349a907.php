

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <?php
                $siteLogo = \App\Models\SiteSetting::where('key', 'site_logo')->value('value');
                $siteName = \App\Models\SiteSetting::where('key', 'site_name')->value('value') ?? 'RisingCare';
            ?>
            
            <?php if($siteLogo): ?>
                <img src="<?php echo e(asset('storage/' . $siteLogo)); ?>" alt="<?php echo e($siteName); ?>" class="h-16 mx-auto mb-4 object-contain">
            <?php endif; ?>
            
            <h2 class="text-3xl font-bold text-gray-800">Registrasi</h2>
            <p class="text-gray-600 mt-2">Bergabung dengan <?php echo e($siteName); ?></p>
        </div>

        <?php if($errors->any()): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('registrasi')); ?>">
            <?php echo csrf_field(); ?>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
                <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Pekerjaan</label>
                <select name="occupation_id" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Pekerjaan</option>
                    <?php $__currentLoopData = $occupations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $occupation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($occupation->id); ?>" <?php echo e(old('occupation_id') == $occupation->id ? 'selected' : ''); ?>>
                            <?php echo e($occupation->name); ?> (<?php echo e($occupation->membership->name); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="text-sm text-gray-500 mt-1">Membership akan otomatis disesuaikan dengan pekerjaan Anda</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                Daftar
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Sudah punya akun? 
            <a href="<?php echo e(route('masuk')); ?>" class="text-blue-600 hover:underline font-medium">Masuk di sini</a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/auth/register.blade.php ENDPATH**/ ?>