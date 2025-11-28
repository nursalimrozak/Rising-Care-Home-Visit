

<?php $__env->startSection('title', 'Layanan Kami - RisingCare'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Section -->
<section class="bg-gradient-to-br from-teal-50 via-gray-50 to-teal-100 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-4">Layanan Kesehatan</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Layanan kesehatan profesional dan terpercaya untuk Anda dan keluarga
            </p>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition border border-gray-100 flex flex-col h-full">
                <div class="h-48 bg-teal-50 flex items-center justify-center">
                    <?php if($service->image): ?>
                        <img src="<?php echo e(asset('storage/' . $service->image)); ?>" alt="<?php echo e($service->name); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <i class="fas fa-stethoscope text-teal-300 text-6xl"></i>
                    <?php endif; ?>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="mb-4">
                        <span class="bg-teal-100 text-teal-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            <?php echo e($service->category->name ?? 'Umum'); ?>

                        </span>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800"><?php echo e($service->name); ?></h3>
                    <p class="text-gray-600 mb-6 flex-1"><?php echo e($service->description); ?></p>
                    
                    <div class="mt-auto pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-gray-500 text-sm">Mulai dari</span>
                            <span class="text-lg font-bold text-teal-600">
                                Rp <?php echo e(number_format($service->prices->min('price') ?? 0, 0, ',', '.')); ?>

                            </span>
                        </div>
                        <a href="<?php echo e(route('booking.create', ['service_id' => $service->id])); ?>" class="block w-full bg-teal-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-teal-700 transition">
                            Booking Sekarang
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-user-md text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada layanan yang tersedia</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<?php if(isset($ctaAppointment)): ?>
<section class="py-20 bg-gradient-to-br from-teal-600 to-teal-700 text-white" <?php if($ctaAppointment->background_image): ?> style="background-image: url('<?php echo e(asset('storage/' . $ctaAppointment->background_image)); ?>'); background-size: cover; background-position: center;" <?php elseif($ctaAppointment->background_color): ?> style="background: <?php echo e($ctaAppointment->background_color); ?>;" <?php endif; ?>>
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6"><?php echo e($ctaAppointment->section_title ?? 'Ready to Get Started?'); ?></h2>
            <p class="text-xl mb-10 text-teal-100">
                <?php echo e($ctaAppointment->section_subtitle ?? 'Buat janji dengan kami sekarang dan dapatkan perawatan kesehatan terbaik untuk Anda dan keluarga'); ?>

            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('booking.create')); ?>" class="bg-white text-teal-600 px-10 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition shadow-xl">
                    <?php echo e($ctaAppointment->button_text ?? 'Buat Janji Sekarang'); ?>

                </a>
                <a href="<?php echo e(route('layanan')); ?>" class="bg-teal-500 text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-teal-400 transition border-2 border-white">
                    Lihat Layanan
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/landing/services.blade.php ENDPATH**/ ?>