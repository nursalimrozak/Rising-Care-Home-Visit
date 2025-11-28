@extends('layouts.app')

@section('title', 'Artikel Kesehatan - RisingCare')

@section('content')
<!-- Header Section -->
<section class="bg-gradient-to-br from-teal-50 via-gray-50 to-teal-100 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-4">Artikel Kesehatan</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Baca artikel kesehatan terbaru dan tips hidup sehat dari para ahli
            </p>
        </div>
    </div>
</section>

<!-- Articles Grid -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($articles as $article)
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition border border-gray-100">
                @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-56 object-cover">
                @else
                <div class="w-full h-56 bg-gradient-to-br from-teal-100 to-blue-100 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-5xl"></i>
                </div>
                @endif
                <div class="p-6">
                    <div class="text-sm text-gray-500 mb-2">
                        <i class="far fa-calendar mr-2"></i>{{ $article->created_at->format('d M Y') }}
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-800">{{ $article->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($article->excerpt, 100) }}</p>
                    <a href="{{ route('artikel.detail', $article->slug) }}" class="text-teal-600 font-semibold hover:underline inline-flex items-center gap-2">
                        Read More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-12">
                <i class="fas fa-newspaper text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada artikel yang dipublikasikan</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($articles->hasPages())
        <div class="mt-12">
            {{ $articles->links() }}
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
@if($ctaAppointment)
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
