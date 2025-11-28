

<?php $__env->startSection('title', 'Profil Saya - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
        <p class="text-gray-600">Kelola informasi pribadi dan keamanan akun Anda.</p>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p class="font-bold">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?php echo e(route('customer.profile.update')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="p-8">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Avatar Section -->
                    <div class="w-full md:w-1/3 flex flex-col items-center">
                        <div class="relative mb-4 group">
                            <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-teal-50 bg-gray-100">
                                <?php if($user->avatar): ?>
                                    <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="<?php echo e($user->name); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center bg-teal-100 text-teal-600 text-5xl font-bold">
                                        <?php echo e(substr($user->name, 0, 1)); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                            <label for="avatar" class="absolute bottom-0 right-0 bg-teal-600 text-white p-3 rounded-full shadow-lg cursor-pointer hover:bg-teal-700 transition transform hover:scale-105">
                                <i class="fas fa-camera"></i>
                                <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </label>
                        </div>
                        <p class="text-sm text-gray-500 text-center">
                            Klik ikon kamera untuk mengubah foto profil.<br>
                            Maksimal 2MB (JPG, PNG).
                        </p>
                    </div>

                    <!-- Form Fields -->
                    <div class="w-full md:w-2/3 space-y-6">
                        <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Informasi Pribadi</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Pekerjaan</label>
                                <input type="text" value="<?php echo e($user->occupation->name ?? '-'); ?>" disabled class="w-full px-4 py-3 border border-gray-200 bg-gray-50 rounded-lg text-gray-500 cursor-not-allowed">
                                <p class="text-xs text-gray-400 mt-1">Hubungi admin untuk mengubah pekerjaan.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Email</label>
                                <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
                                <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Tanggal Lahir</label>
                                <input type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth', $user->date_of_birth)); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Nomor BPJS</label>
                                <input type="text" name="bpjs_number" value="<?php echo e(old('bpjs_number', $user->bpjs_number)); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Opsional">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Jenis Kelamin</label>
                                <select name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male" <?php echo e(old('gender', $user->gender) == 'male' ? 'selected' : ''); ?>>Laki-laki</option>
                                    <option value="female" <?php echo e(old('gender', $user->gender) == 'female' ? 'selected' : ''); ?>>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Agama</label>
                                <select name="religion" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    <option value="">Pilih Agama</option>
                                    <?php $__currentLoopData = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $religion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($religion); ?>" <?php echo e(old('religion', $user->religion) == $religion ? 'selected' : ''); ?>><?php echo e($religion); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Status Pernikahan</label>
                                <select name="marital_status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    <option value="">Pilih Status</option>
                                    <?php $__currentLoopData = ['Belum Menikah', 'Menikah', 'Cerai Hidup', 'Cerai Mati']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($status); ?>" <?php echo e(old('marital_status', $user->marital_status) == $status ? 'selected' : ''); ?>><?php echo e($status); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"><?php echo e(old('address', $user->address)); ?></textarea>
                        </div>

                        <div class="pt-6 mt-6 border-t border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Kontak Darurat</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                                    <input type="text" name="emergency_contact_name" value="<?php echo e(old('emergency_contact_name', $user->emergency_contact_name)); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Hubungan</label>
                                    <select name="emergency_contact_relationship" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        <option value="">Pilih Hubungan</option>
                                        <?php $__currentLoopData = ['Suami', 'Isteri', 'Anak Kandung', 'Anak Angkat', 'Saudara', 'Rekan Kerja', 'Tetangga']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($rel); ?>" <?php echo e(old('emergency_contact_relationship', $user->emergency_contact_relationship) == $rel ? 'selected' : ''); ?>><?php echo e($rel); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
                                <input type="text" name="emergency_contact_phone" value="<?php echo e(old('emergency_contact_phone', $user->emergency_contact_phone)); ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Alamat Lengkap</label>
                                <textarea name="emergency_contact_address" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"><?php echo e(old('emergency_contact_address', $user->emergency_contact_address)); ?></textarea>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Ganti Password</h3>
                            <p class="text-sm text-gray-500 mb-4">Kosongkan jika tidak ingin mengubah password.</p>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-700 font-medium mb-2">Password Saat Ini</label>
                                    <input type="password" name="current_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2">Password Baru</label>
                                        <input type="password" name="new_password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-2">Konfirmasi Password Baru</label>
                                        <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-teal-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-teal-700 transition shadow-lg flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Find the image element inside the parent container
                var img = input.closest('.group').querySelector('img');
                if (img) {
                    img.src = e.target.result;
                } else {
                    // If there was no image (just initials), replace the div with an img
                    var container = input.closest('.group').querySelector('.w-40');
                    container.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/customer/profile.blade.php ENDPATH**/ ?>