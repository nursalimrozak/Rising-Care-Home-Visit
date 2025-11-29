@extends('layouts.app')

@section('title', 'RisingCare - Layanan Kesehatan Terpercaya')

@section('content')
<!-- Hero Slider Section -->
<section class="bg-gradient-to-br from-teal-50 via-gray-50 to-teal-100 py-20 relative">
    <div class="container mx-auto px-4">
        <div class="swiper heroSwiper">
            <div class="swiper-wrapper">
                @forelse($heroSlides->where('is_active', true) as $slide)
                <div class="swiper-slide">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                        <div>
                            <h1 class="text-5xl lg:text-6xl font-bold text-gray-800 mb-6 leading-tight">
                                {!! str_replace('Good Health', '<span class="text-teal-600">Good Health</span>', $slide->title) !!}
                            </h1>
                            <p class="text-xl text-gray-600 mb-8">
                                {{ $slide->subtitle ?? 'Perawatan kesehatan profesional dengan tenaga medis berpengalaman untuk keluarga Indonesia' }}
                            </p>
                            <div class="flex gap-4">
                                <a href="{{ $slide->cta_link ?? route('booking.create') }}" class="bg-teal-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-teal-700 transition shadow-lg">
                                    {{ $slide->cta_text ?? 'Buat Janji' }}
                                </a>
                                <a href="{{ route('layanan') }}" class="bg-white text-gray-800 px-8 py-4 rounded-full font-semibold hover:bg-gray-50 transition shadow-lg border border-gray-200">
                                    Lihat Layanan
                                </a>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="bg-teal-100 rounded-3xl p-8 shadow-2xl">
                                @if($slide->background_image)
                                    <img src="{{ asset('storage/' . $slide->background_image) }}" alt="{{ $slide->title }}" class="w-full h-96 object-cover rounded-2xl">
                                @else
                                    <div class="bg-white rounded-2xl p-4 h-96 flex items-center justify-center">
                                        <div class="text-center text-gray-400">
                                            <i class="fas fa-user-md text-8xl mb-4"></i>
                                            <p class="text-lg">Foto Hero Image</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <!-- Floating Stats -->
                            @php
                                $happyPatients = \App\Models\SiteSetting::where('key', 'stat_happy_patients')->value('value') ?? '1000';
                            @endphp
                            <div class="absolute -bottom-6 -left-6 bg-white rounded-2xl shadow-xl p-6">
                                <div class="flex items-center gap-4">
                                    <div class="bg-teal-100 w-16 h-16 rounded-full flex items-center justify-center">
                                        <i class="fas fa-heartbeat text-teal-600 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold text-gray-800">{{ $happyPatients }}+</p>
                                        <p class="text-sm text-gray-600">Pasien Puas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
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
                @endforelse
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
                <a href="{{ route('layanan') }}" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
            </div>
            <div class="bg-white border-2 border-gray-100 rounded-2xl p-8 hover:border-teal-600 hover:shadow-xl transition cursor-pointer">
                <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-home text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Home Visit</h3>
                <p class="text-gray-600 mb-4">Perawatan kesehatan di rumah Anda</p>
                <a href="{{ route('layanan') }}" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
            </div>
            <div class="bg-white border-2 border-gray-100 rounded-2xl p-8 hover:border-teal-600 hover:shadow-xl transition cursor-pointer">
                <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-user-nurse text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Expert Treatment</h3>
                <p class="text-gray-600 mb-4">Perawatan oleh tenaga medis berpengalaman</p>
                <a href="{{ route('layanan') }}" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
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
                    @if(isset($sectionSettings['landing_about_image']) && $sectionSettings['landing_about_image'])
                        <img src="{{ asset('storage/' . $sectionSettings['landing_about_image']) }}" alt="About Us" class="rounded-2xl w-full h-auto object-cover shadow-lg">
                    @else
                        <div class="bg-white rounded-2xl p-4 h-96 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <i class="fas fa-user-md text-8xl mb-4"></i>
                                <p class="text-lg">Foto Tim Medis</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <h2 class="text-4xl font-bold text-gray-800 mb-6">
                    {{ $sectionSettings['landing_about_title'] ?? 'Empower Your Life With Healthcare Care' }}
                </h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    {{ $sectionSettings['landing_about_description'] ?? 'RisingCare menyediakan layanan kesehatan berkualitas dengan tenaga medis profesional dan berpengalaman. Kami berkomitmen untuk memberikan perawatan terbaik untuk Anda dan keluarga.' }}
                </p>
                
                @php
                    $points = json_decode($sectionSettings['landing_about_points'] ?? '[]', true);
                    if (empty($points)) {
                        $points = [
                            'Tenaga medis profesional dan bersertifikat',
                            'Layanan home visit tersedia',
                            'Sistem membership dengan benefit menarik'
                        ];
                    }
                @endphp
                
                <ul class="space-y-4 mb-8">
                    @foreach($points as $point)
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-teal-600 text-xl mt-1"></i>
                        <span class="text-gray-700">{{ $point }}</span>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('booking.create') }}" class="bg-teal-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-teal-700 transition shadow-lg inline-block">
                    Buat Janji Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Achievement Stats -->
@php
    $statPatients = \App\Models\SiteSetting::get('stat_happy_patients', '1000');
    $statDoctors = \App\Models\SiteSetting::get('stat_expert_doctors', '35');
    $statYears = \App\Models\SiteSetting::get('stat_years_experience', '15');
    $statRooms = \App\Models\SiteSetting::get('stat_clinic_rooms', '5');
@endphp
<section class="py-16 bg-gradient-to-r from-gray-700 to-gray-800 text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="bg-white/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-4xl"></i>
                </div>
                <p class="text-4xl font-bold mb-2">{{ $statPatients }}+</p>
                <p class="text-gray-300">Happy Patients</p>
            </div>
            <div>
                <div class="bg-white/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-md text-4xl"></i>
                </div>
                <p class="text-4xl font-bold mb-2">{{ $statDoctors }}</p>
                <p class="text-gray-300">Expert Doctors</p>
            </div>
            <div>
                <div class="bg-white/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-award text-4xl"></i>
                </div>
                <p class="text-4xl font-bold mb-2">{{ $statYears }}+</p>
                <p class="text-gray-300">Years Experience</p>
            </div>
            <div>
                <div class="bg-white/10 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-hospital text-4xl"></i>
                </div>
                <p class="text-4xl font-bold mb-2">{{ $statRooms }}</p>
                <p class="text-gray-300">Clinic Rooms</p>
            </div>
        </div>
    </div>
</section>

<!-- Best Services -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">{{ $sectionSettings['landing_services_title'] ?? 'Best Services' }}</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                {{ $sectionSettings['landing_services_subtitle'] ?? 'Kami menyediakan berbagai layanan kesehatan terbaik untuk memenuhi kebutuhan Anda' }}
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($servicesHighlight as $service)
            <div class="bg-gradient-to-br from-teal-50 to-white rounded-2xl p-8 hover:shadow-2xl transition border border-gray-100">
                <div class="bg-teal-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-{{ $service->icon ?? 'heartbeat' }} text-white text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-gray-800">{{ $service->title }}</h3>
                <p class="text-gray-600 mb-6">{{ $service->description }}</p>
                <a href="{{ route('layanan') }}" class="text-teal-600 font-semibold hover:underline">
                    Learn More →
                </a>
            </div>
            @empty
            <div class="col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gradient-to-br from-teal-50 to-white rounded-2xl p-8 border border-gray-100">
                        <div class="bg-teal-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-stethoscope text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Health Checkup</h3>
                        <p class="text-gray-600 mb-6">Pemeriksaan kesehatan rutin dan menyeluruh untuk deteksi dini</p>
                        <a href="{{ route('layanan') }}" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-white rounded-2xl p-8 border border-gray-100">
                        <div class="bg-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-home text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Home Visit</h3>
                        <p class="text-gray-600 mb-6">Layanan perawatan kesehatan langsung di rumah Anda</p>
                        <a href="{{ route('layanan') }}" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-white rounded-2xl p-8 border border-gray-100">
                        <div class="bg-purple-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                            <i class="fas fa-user-nurse text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-gray-800">Expert Care</h3>
                        <p class="text-gray-600 mb-6">Perawatan oleh tenaga medis profesional berpengalaman</p>
                        <a href="{{ route('layanan') }}" class="text-teal-600 font-semibold hover:underline">Learn More →</a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- How We Work -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">{{ $sectionSettings['landing_how_we_work_title'] ?? 'How We Work' }}</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                {{ $sectionSettings['landing_how_we_work_subtitle'] ?? 'Proses mudah dan cepat untuk mendapatkan layanan kesehatan terbaik' }}
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @forelse($howWeWork as $step)
            <div class="text-center">
                <div class="bg-gradient-to-br from-teal-500 to-teal-600 text-white w-20 h-20 rounded-2xl flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg">
                    {{ $step->step_number }}
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">{{ $step->title }}</h3>
                <p class="text-gray-600">{{ $step->description }}</p>
            </div>
            @empty
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
            @endforelse
        </div>
    </div>
</section>

<!-- Testimonials Slider -->
@if($testimonials->count() > 0)
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">{{ $sectionSettings['landing_reviews_title'] ?? 'What Our Patients Say' }}</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                {{ $sectionSettings['landing_reviews_subtitle'] ?? 'Testimoni dari pasien yang puas dengan layanan kami' }}
            </p>
        </div>
        
        <div class="swiper testimonialSwiper max-w-6xl mx-auto">
            <div class="swiper-wrapper">
                @foreach($testimonials as $testimonial)
                <div class="swiper-slide">
                    <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition h-full mb-4">
                        <div class="flex items-center mb-6">
                            @if($testimonial->customer && $testimonial->customer->avatar)
                                <img src="{{ asset('storage/' . $testimonial->customer->avatar) }}" alt="{{ $testimonial->customer->name }}" class="w-16 h-16 rounded-full object-cover mr-4">
                            @else
                                <div class="w-16 h-16 rounded-full bg-teal-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-teal-600 text-2xl"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $testimonial->customer->name ?? 'Customer' }}</h4>
                                <p class="text-sm text-gray-500">{{ optional($testimonial->booking->service)->name ?? 'Layanan RisingCare' }}</p>
                                <div class="flex text-yellow-400 mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $testimonial->rating ? '' : ' text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600 italic">"{{ $testimonial->comment }}"</p>
                    </div>
                </div>
                @endforeach
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
@endif

<!-- Blog & Articles -->
@if($articles->count() > 0)
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">{{ $sectionSettings['landing_articles_title'] ?? 'Blog & Article' }}</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">
                {{ $sectionSettings['landing_articles_subtitle'] ?? 'Baca artikel kesehatan terbaru dan tips hidup sehat dari para ahli' }}
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($articles->take(3) as $article)
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition border border-gray-100">
                @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-56 object-cover">
                @else
                <div class="w-full h-56 bg-gradient-to-br from-teal-100 to-blue-100 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-5xl"></i>
                </div>
                @endif
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-3 text-gray-800">{{ $article->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($article->excerpt, 100) }}</p>
                    <a href="{{ route('artikel.detail', $article->slug) }}" class="text-teal-600 font-semibold hover:underline inline-flex items-center gap-2">
                        Read More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="{{ route('artikel') }}" class="inline-block bg-teal-600 text-white px-8 py-4 rounded-full font-semibold hover:bg-teal-700 transition shadow-lg">
                Lihat Semua Artikel <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-teal-600 to-teal-700 text-white" @if($ctaAppointment && $ctaAppointment->background_image) style="background-image: url('{{ asset('storage/' . $ctaAppointment->background_image) }}'); background-size: cover; background-position: center;" @elseif($ctaAppointment && $ctaAppointment->background_color) style="background: {{ $ctaAppointment->background_color }};" @endif>
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">{{ $ctaAppointment->section_title ?? 'Ready to Get Started?' }}</h2>
            <p class="text-xl mb-10 text-teal-100">
                {{ $ctaAppointment->section_subtitle ?? 'Buat janji dengan kami sekarang dan dapatkan perawatan kesehatan terbaik untuk Anda dan keluarga' }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('booking.create') }}" class="bg-white text-teal-600 px-10 py-4 rounded-full font-bold text-lg hover:bg-gray-100 transition shadow-xl">
                    {{ $ctaAppointment->button_text ?? 'Buat Janji Sekarang' }}
                </a>
                <a href="{{ route('layanan') }}" class="bg-teal-500 text-white px-10 py-4 rounded-full font-bold text-lg hover:bg-teal-400 transition border-2 border-white">
                    Lihat Layanan
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Pop-up Modal -->
@if($popups->count() > 0)
<div x-data="{ showPopup: true }" x-show="showPopup" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.75);">
    <div @click.away="showPopup = false" class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
        <!-- Close Button -->
        <button @click="showPopup = false" class="absolute top-4 right-4 z-10 bg-white/90 hover:bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-lg transition">
            <i class="fas fa-times text-xl"></i>
        </button>

        <!-- Swiper Container -->
        <div class="swiper popupSwiper">
            <div class="swiper-wrapper">
                @foreach($popups as $popup)
                <div class="swiper-slide">
                    @if($popup->link)
                        <a href="{{ $popup->link }}" target="_blank" class="block">
                            <img src="{{ asset('storage/' . $popup->image) }}" alt="{{ $popup->title }}" class="w-full h-auto object-contain max-h-[80vh]">
                        </a>
                    @else
                        <img src="{{ asset('storage/' . $popup->image) }}" alt="{{ $popup->title }}" class="w-full h-auto object-contain max-h-[80vh]">
                    @endif
                </div>
                @endforeach
            </div>

            @if($popups->count() > 1)
            <!-- Navigation Arrows -->
            <div class="swiper-button-prev !text-white !w-10 !h-10 !bg-teal-600 !rounded-full after:!text-sm"></div>
            <div class="swiper-button-next !text-white !w-10 !h-10 !bg-teal-600 !rounded-full after:!text-sm"></div>

            <!-- Pagination Dots -->
            <div class="swiper-pagination !bottom-4"></div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($popups->count() > 1)
    new Swiper('.popupSwiper', {
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
    });
    @endif
});
</script>
@endif
@endsection
