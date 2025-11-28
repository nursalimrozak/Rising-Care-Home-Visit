

<?php $__env->startSection('title', 'RisingCare - Layanan Kesehatan Terpercaya'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Slider Section -->
<section class="bg-gradient-to-br from-teal-50 via-gray-50 to-teal-100 py-20 relative">
    <div class="container mx-auto px-4">
        <div class="swiper heroSwiper">
            <div class="swiper-wrapper">
                <?php $__empty_1 = true; $__currentLoopData = $heroSlides->where('is_active', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="swiper-slide">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h1 class="text-5xl lg:text-6xl font-bold text-gray-800 mb-6 leading-tight">
                                <?php echo str_replace('Good Health', '<span class="text-teal-600">Good Health</span>', $slide->title); ?>

                            </h1>
                            <p class="text-xl text-gray-600 mb-8">
                                <?php echo e($slide->subtitle ?? 'Perawatan kesehatan profesional dengan tenaga medis berpengalaman untuk keluarga Indonesia'); ?>

                            </p>
                            <div class="flex gap-4">
                                <a href="<?php echo e($slide->cta_link ?? route('booking.create')); ?>" class="bg-teal-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-teal-700 transition shadow-lg">
                                    <?php echo e($slide->cta_text ?? 'Buat Janji'); ?>

                                </a>
                                <a href="<?php echo e(route('layanan')); ?>" class="bg-white text-gray-800 px-8 py-4 rounded-full font-semibold hover:bg-gray-50 transition shadow-lg border border-gray-200">
                                    Lihat Layanan
                                </a>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="bg-teal-100 rounded-3xl p-8 shadow-2xl">
                                <?php if($slide->background_image): ?>
                                    <img src="<?php echo e(asset('storage/' . $slide->background_image)); ?>" alt="<?php echo e($slide->title); ?>" class="w-full h-96 object-cover rounded-2xl">
                                <?php else: ?>
                                    <div class="bg-white rounded-2xl p-4 h-96 flex items-center justify-center">
                                        <div class="text-center text-gray-400">
                                            <i class="fas fa-user-md text-8xl mb-4"></i>
                                            <p class="text-lg">Foto Hero Image</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Floating Stats -->
                            <?php
                                $happyPatients = \App\Models\SiteSetting::where('key', 'stat_happy_patients')->value('value') ?? '1000';
                            ?>
                            <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl p-6">
                                <div class="flex items-center gap-4">
                                    <div class="bg-teal-100 w-16 h-16 rounded-full flex items-center justify-center">
                                        <i class="fas fa-heartbeat text-teal-600 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-gray-800"><?php echo e($happyPatients); ?>+</p>
                                        <p class="text-sm text-gray-600">Pasien Puas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="swiper-slide">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h1 class="text-5xl lg:text-6xl font-bold text-gray-800 mb-6 leading-tight">
                                The Backbone of <span class="text-teal-600">Good Health</span>
                            </h1>
                            <p class="text-xl text-gray-600 mb-8">
                                Perawatan kesehatan profesional dengan tenaga medis berpengalaman untuk keluarga Indonesia
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination !bottom-0"></div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.heroSwiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
});
</script>

<!-- Services Quick Access -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Are You Looking For</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            <div class="bg-white border-2 border-gray-100 rounded-2xl p-8 hover:border-teal-600 hover:shadow-xl transition cursor-pointer">
                <div class="bg-teal-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-stethoscope text-teal-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Health Checkup</h3>
                <p class="text-gray-600 mb-4">Pemeriksaan kesehatan rutin dan menyeluruh</p>
                <a href="<?php echo e(route('layanan')); ?>" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
            </div>
            <div class="bg-white border-2 border-gray-100 rounded-2xl p-8 hover:border-teal-600 hover:shadow-xl transition cursor-pointer">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-home text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Home Visit</h3>
                <p class="text-gray-600 mb-4">Perawatan kesehatan di rumah Anda</p>
                <a href="<?php echo e(route('layanan')); ?>" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
            </div>
            <div class="bg-white border-2 border-gray-100 rounded-2xl p-8 hover:border-teal-600 hover:shadow-xl transition cursor-pointer">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-user-nurse text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Expert Treatment</h3>
                <p class="text-gray-600 mb-4">Perawatan oleh tenaga medis berpengalaman</p>
                <a href="<?php echo e(route('layanan')); ?>" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-teal-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <div class="bg-teal-600 rounded-3xl p-8 shadow-2xl">
                    <?php if(isset($sectionSettings['landing_about_image']) && $sectionSettings['landing_about_image']): ?>
                        <img src="<?php echo e(asset('storage/' . $sectionSettings['landing_about_image'])); ?>" alt="About Us" class="rounded-2xl w-full h-auto object-cover shadow-lg">
                    <?php else: ?>
                        <div class="bg-white rounded-2xl p-4 h-96 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <i class="fas fa-user-md text-8xl mb-4"></i>
                                <p class="text-lg">Foto Tim Medis</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <h2 class="text-4xl font-bold text-gray-800 mb-6">
                    <?php echo e($sectionSettings['landing_about_title'] ?? 'Empower Your Life With Healthcare Care'); ?>

                </h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    <?php echo e($sectionSettings['landing_about_description'] ?? 'RisingCare menyediakan layanan kesehatan berkualitas dengan tenaga medis profesional dan berpengalaman. Kami berkomitmen untuk memberikan perawatan terbaik untuk Anda dan keluarga.'); ?>

                </p>
                
                <?php
                    $points = json_decode($sectionSettings['landing_about_points'] ?? '[]', true);
                    if (empty($points)) {
                        $points = [
                            'Tenaga medis profesional dan bersertifikat',
                            'Layanan home visit tersedia',
                            'Sistem membership dengan benefit menarik'
                        ];
                    }
                ?>
                
                <ul class="space-y-4 mb-8">
                    <?php $__currentLoopData = $points; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-teal-600 text-xl mt-1"></i>
                        <span class="text-gray-700"><?php echo e($point); ?></span>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <a href="<?php echo e(route('booking.create')); ?>" class="bg-teal-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-teal-700 transition shadow-lg inline-block">
                    Buat Janji Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Achievement Stats -->
<?php
    $statPatients = \App\Models\SiteSetting::get('stat_happy_patients', '1000');
    $statDoctors = \App\Models\SiteSetting::get('stat_expert_doctors', '35');
    $statYears = \App\Models\SiteSetting::get('stat_years_experience', '15');
    $statRooms = \App\Models\SiteSetting::get('stat_clinic_rooms', '5');
?>
<section class="py-16 bg-gradient-to-r from-gray-700 to-gray-800 text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="bg-white/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-4xl"></i>
                </div>
                <p class="text-4xl font-bold mb-2"><?php echo e($statPatients); ?>+</p>
                <p class="text-gray-300">Happy Patients</p>
            </div>
            <div>
                <div class="bg-white/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-md text-4xl"></i>
                </div>
                <p class="text-4xl font-bold mb-2"><?php echo e($statDoctors); ?></p>
                <p class="text-gray-300">Expert Doctors</p>
            </div>
            <div>
                <div class="bg-white/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-award text-4xl"></i>
                </div>
                <p class="text-4xl font-bold mb-2"><?php echo e($statYears); ?>+</p>
                <p class="text-gray-300">Years Experience</p>
            </div>
            <div>
                <div class="bg-white/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-hospital text-4xl"></i>
                </div>
                <p class="text-4xl font-bold mb-2"><?php echo e($statRooms); ?></p>
                <p class="text-gray-300">Clinic Rooms</p>
            </div>
        </div>
    </div>
</section>

<!-- Best Services -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4"><?php echo e($sectionSettings['landing_services_title'] ?? 'Best Services'); ?></h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                <?php echo e($sectionSettings['landing_services_subtitle'] ?? 'Kami menyediakan berbagai layanan kesehatan terbaik untuk memenuhi kebutuhan Anda'); ?>

            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $servicesHighlight; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-gradient-to-br from-teal-50 to-white rounded-2xl p-8 hover:shadow-2xl transition border border-gray-100">
                <div class="bg-teal-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-<?php echo e($service->icon ?? 'heartbeat'); ?> text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800"><?php echo e($service->title); ?></h3>
                <p class="text-gray-600 mb-6"><?php echo e($service->description); ?></p>
                <a href="<?php echo e(route('layanan')); ?>" class="text-teal-600 font-semibold hover:underline">
                    Learn More →
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gradient-to-br from-teal-50 to-white rounded-2xl p-8 border border-gray-100">
                        <div class="bg-teal-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-stethoscope text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Health Checkup</h3>
                        <p class="text-gray-600 mb-6">Pemeriksaan kesehatan rutin dan menyeluruh untuk deteksi dini</p>
                        <a href="<?php echo e(route('layanan')); ?>" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 border border-gray-100">
                        <div class="bg-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-home text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Home Visit</h3>
                        <p class="text-gray-600 mb-6">Layanan perawatan kesehatan langsung di rumah Anda</p>
                        <a href="<?php echo e(route('layanan')); ?>" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-white rounded-2xl p-8 border border-gray-100">
                        <div class="bg-purple-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-user-nurse text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Expert Care</h3>
                        <p class="text-gray-600 mb-6">Perawatan oleh tenaga medis profesional berpengalaman</p>
                        <a href="<?php echo e(route('layanan')); ?>" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- How We Work -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4"><?php echo e($sectionSettings['landing_how_we_work_title'] ?? 'How We Work'); ?></h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                <?php echo e($sectionSettings['landing_how_we_work_subtitle'] ?? 'Proses mudah dan cepat untuk mendapatkan layanan kesehatan terbaik'); ?>

            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <?php $__empty_1 = true; $__currentLoopData = $howWeWork; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="text-center">
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 text-white w-20 h-20 rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg">
                    <?php echo e($step->step_number); ?>

                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800"><?php echo e($step->title); ?></h3>
                <p class="text-gray-600"><?php echo e($step->description); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center">
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 text-white w-20 h-20 rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg">1</div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Make Appointment</h3>
                <p class="text-gray-600">Buat janji dengan mudah melalui website kami</p>
            </div>
            <div class="text-center">
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 text-white w-20 h-20 rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg">2</div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Get Confirmation</h3>
                <p class="text-gray-600">Dapatkan konfirmasi dan jadwal perawatan</p>
            </div>
            <div class="text-center">
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 text-white w-20 h-20 rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg">3</div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Receive Treatment</h3>
                <p class="text-gray-600">Terima perawatan dari tenaga medis profesional</p>
            </div>
            <div class="text-center">
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 text-white w-20 h-20 rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg">4</div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Get Well Soon</h3>
                <p class="text-gray-600">Pulih dengan cepat dan sehat kembali</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Testimonials Slider -->
<?php if($testimonials->count() > 0): ?>
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4"><?php echo e($sectionSettings['landing_reviews_title'] ?? 'What Our Patients Say'); ?></h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                <?php echo e($sectionSettings['landing_reviews_subtitle'] ?? 'Testimoni dari pasien yang puas dengan layanan kami'); ?>

            </p>
        </div>
        
        <div class="swiper testimonialSwiper max-w-6xl mx-auto">
            <div class="swiper-wrapper">
                <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="swiper-slide">
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition h-full mb-4">
                        <div class="flex items-center mb-6">
                            <?php if($testimonial->customer && $testimonial->customer->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . $testimonial->customer->avatar)); ?>" alt="<?php echo e($testimonial->customer->name); ?>" class="w-16 h-16 rounded-full object-cover mr-4">
                            <?php else: ?>
                                <div class="w-16 h-16 rounded-full bg-teal-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-teal-600 text-2xl"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h4 class="font-bold text-gray-800"><?php echo e($testimonial->customer->name ?? 'Customer'); ?></h4>
                                <p class="text-sm text-gray-500"><?php echo e(optional($testimonial->booking->service)->name ?? 'Layanan RisingCare'); ?></p>
                                <div class="flex text-yellow-400 mt-1">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star<?php echo e($i <= $testimonial->rating ? '' : ' text-gray-300'); ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"<?php echo e($testimonial->comment); ?>"</p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination !relative !mt-8"></div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.testimonialSwiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        },
    });
});
</script>
<?php endif; ?>

<!-- Blog & Articles -->
<?php if($articles->count() > 0): ?>
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4"><?php echo e($sectionSettings['landing_articles_title'] ?? 'Blog & Article'); ?></h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                <?php echo e($sectionSettings['landing_articles_subtitle'] ?? 'Baca artikel kesehatan terbaru dan tips hidup sehat dari para ahli'); ?>

            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php $__currentLoopData = $articles->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition border border-gray-100">
                <?php if($article->featured_image): ?>
                <img src="<?php echo e(asset('storage/' . $article->featured_image)); ?>" alt="<?php echo e($article->title); ?>" class="w-full h-56 object-cover">
                <?php else: ?>
                <div class="w-full h-56 bg-gradient-to-br from-teal-100 to-blue-100 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-5xl"></i>
                </div>
                <?php endif; ?>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-3 text-gray-800"><?php echo e($article->title); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo e(Str::limit($article->excerpt, 100)); ?></p>
                    <a href="<?php echo e(route('artikel.detail', $article->slug)); ?>" class="text-teal-600 font-semibold hover:underline inline-flex items-center gap-2">
                        Read More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="<?php echo e(route('artikel')); ?>" class="inline-block bg-teal-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-teal-700 transition shadow-lg">
                Lihat Semua Artikel <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-teal-600 to-teal-700 text-white" <?php if($ctaAppointment && $ctaAppointment->background_image): ?> style="background-image: url('<?php echo e(asset('storage/' . $ctaAppointment->background_image)); ?>'); background-size: cover; background-position: center;" <?php elseif($ctaAppointment && $ctaAppointment->background_color): ?> style="background: <?php echo e($ctaAppointment->background_color); ?>;" <?php endif; ?>>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\laragon\www\risingcare\resources\views/landing/index.blade.php ENDPATH**/ ?>