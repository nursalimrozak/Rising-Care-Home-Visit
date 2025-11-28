@extends('layouts.app')

@section('title', 'Layanan Kami - RisingCare')

@section('content')
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
            @forelse($services as $service)
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition border border-gray-100 flex flex-col h-full">
                <div class="h-48 bg-teal-50 flex items-center justify-center">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-stethoscope text-teal-300 text-6xl"></i>
                    @endif
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="mb-4">
                        <span class="bg-teal-100 text-teal-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                            {{ $service->category->name ?? 'Umum' }}
                        </span>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">{{ $service->name }}</h3>
                    <p class="text-gray-600 mb-6 flex-1">{{ $service->description }}</p>
                    
                    <div class="mt-auto pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-gray-500 text-sm">Mulai dari</span>
                            <span class="text-lg font-bold text-teal-600">
                                Rp {{ number_format($service->prices->min('price') ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <a href="{{ route('booking.create', ['service_id' => $service->id]) }}" class="block w-full bg-teal-600 text-white text-center py-3 rounded-xl font-semibold hover:bg-teal-700 transition">
                            Booking Sekarang
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-user-md text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada layanan yang tersedia</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
@if(isset($ctaAppointment))
<section class="py-20 bg-gradient-to-br from-teal-600 to-teal-700 text-white" @if($ctaAppointment->background_image) style="background-image: url('{{ asset('storage/' . $ctaAppointment->background_image) }}'); background-size: cover; background-position: center;" @elseif($ctaAppointment->background_color) style="background: {{ $ctaAppointment->background_color }};" @endif>
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
@endif
@endsection
