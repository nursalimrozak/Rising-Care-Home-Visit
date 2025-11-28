

<?php $__env->startSection('title', 'Site Settings - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Site Settings</h1>
    <p class="text-gray-600">Kelola pengaturan situs, branding, dan SEO</p>
</div>

<form action="<?php echo e(route('admin.settings.update')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Branding & SEO Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 lg:col-span-2">
            <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Branding & SEO</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Logo & Favicon -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Site Logo</label>
                        <?php if($settings['site_logo'] ?? false): ?>
                            <div class="mb-2 p-2 bg-gray-100 rounded-lg inline-block">
                                <img src="<?php echo e(asset('storage/' . $settings['site_logo'])); ?>" alt="Site Logo" class="h-12 object-contain">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="settings[site_logo]" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <p class="text-xs text-gray-500 mt-1">Format: PNG/JPG/SVG. Max: 2MB.</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Favicon</label>
                        <?php if($settings['site_favicon'] ?? false): ?>
                            <div class="mb-2 p-2 bg-gray-100 rounded-lg inline-block">
                                <img src="<?php echo e(asset('storage/' . $settings['site_favicon'])); ?>" alt="Favicon" class="h-8 w-8 object-contain">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="settings[site_favicon]" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <p class="text-xs text-gray-500 mt-1">Format: ICO/PNG. Max: 1MB.</p>
                    </div>
                </div>

                <!-- Meta Tags -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Meta Keywords</label>
                        <textarea name="settings[meta_keywords]" rows="3" placeholder="kesehatan, home care, dokter, perawat"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"><?php echo e(old('settings.meta_keywords', $settings['meta_keywords'] ?? '')); ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma (,)</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Meta Description</label>
                        <textarea name="settings[meta_description]" rows="3" placeholder="Deskripsi singkat situs untuk mesin pencari"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"><?php echo e(old('settings.meta_description', $settings['meta_description'] ?? '')); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- General Settings -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">General Information</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Site Name</label>
                    <input type="text" name="settings[site_name]" 
                        value="<?php echo e(old('settings.site_name', $settings['site_name'] ?? 'RisingCare')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Tagline</label>
                    <input type="text" name="settings[site_tagline]" 
                        value="<?php echo e(old('settings.site_tagline', $settings['site_tagline'] ?? 'Layanan Kesehatan Terpercaya')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Description (Footer)</label>
                    <textarea name="settings[site_description]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"><?php echo e(old('settings.site_description', $settings['site_description'] ?? '')); ?></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Contact Phone</label>
                    <input type="text" name="settings[contact_phone]" 
                        value="<?php echo e(old('settings.contact_phone', $settings['contact_phone'] ?? '')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Contact Email</label>
                    <input type="email" name="settings[contact_email]" 
                        value="<?php echo e(old('settings.contact_email', $settings['contact_email'] ?? '')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Address</label>
                    <textarea name="settings[contact_address]" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"><?php echo e(old('settings.contact_address', $settings['contact_address'] ?? '')); ?></textarea>
                </div>
            </div>
        </div>

        <!-- Social Media Section -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Social Media</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fab fa-facebook text-blue-600 mr-2"></i> Facebook URL
                    </label>
                    <input type="url" name="settings[social_facebook]" 
                        value="<?php echo e(old('settings.social_facebook', $settings['social_facebook'] ?? '')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" placeholder="https://facebook.com/...">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fab fa-instagram text-pink-600 mr-2"></i> Instagram URL
                    </label>
                    <input type="url" name="settings[social_instagram]" 
                        value="<?php echo e(old('settings.social_instagram', $settings['social_instagram'] ?? '')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" placeholder="https://instagram.com/...">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fab fa-twitter text-blue-400 mr-2"></i> Twitter/X URL
                    </label>
                    <input type="url" name="settings[social_twitter]" 
                        value="<?php echo e(old('settings.social_twitter', $settings['social_twitter'] ?? '')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" placeholder="https://twitter.com/...">
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Achievement Stats</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-users text-teal-600 mr-2"></i> Happy Patients
                    </label>
                    <input type="text" name="settings[stat_happy_patients]" 
                        value="<?php echo e(old('settings.stat_happy_patients', $settings['stat_happy_patients'] ?? '1000')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-user-md text-teal-600 mr-2"></i> Expert Doctors
                    </label>
                    <input type="text" name="settings[stat_expert_doctors]" 
                        value="<?php echo e(old('settings.stat_expert_doctors', $settings['stat_expert_doctors'] ?? '35')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-award text-teal-600 mr-2"></i> Years Experience
                    </label>
                    <input type="text" name="settings[stat_years_experience]" 
                        value="<?php echo e(old('settings.stat_years_experience', $settings['stat_years_experience'] ?? '15')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-hospital text-teal-600 mr-2"></i> Clinic Rooms
                    </label>
                    <input type="text" name="settings[stat_clinic_rooms]" 
                        value="<?php echo e(old('settings.stat_clinic_rooms', $settings['stat_clinic_rooms'] ?? '5')); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex justify-end">
        <button type="submit" class="bg-teal-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-teal-700 transition shadow-lg">
            <i class="fas fa-save mr-2"></i> Simpan Pengaturan
        </button>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>