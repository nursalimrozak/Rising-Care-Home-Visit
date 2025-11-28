@extends('layouts.app')

@section('title', $article->title . ' - RisingCare')

@section('content')
<!-- Header -->
<section class="bg-gradient-to-br from-teal-50 via-gray-50 to-teal-100 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-4xl mx-auto">
            <div class="mb-4">
                <span class="bg-teal-100 text-teal-800 text-sm font-semibold px-3 py-1 rounded-full">
                    {{ $article->category ?? 'General' }}
                </span>
            </div>
            <h1 class="text-3xl lg:text-5xl font-bold text-gray-800 mb-6 leading-tight">{{ $article->title }}</h1>
            <div class="flex items-center justify-center text-gray-500 text-sm">
                <span class="mr-4"><i class="far fa-calendar mr-2"></i>{{ $article->published_at ? $article->published_at->format('d M Y') : $article->created_at->format('d M Y') }}</span>
                <span><i class="far fa-user mr-2"></i>{{ $article->author->name ?? 'Admin' }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Content -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            @if($article->featured_image)
            <div class="mb-10 rounded-2xl overflow-hidden shadow-lg">
                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-auto">
            </div>
            @endif

            <div class="prose prose-lg max-w-none text-gray-600">
                {!! $article->content !!}
            </div>

            <!-- Share / Tags if needed -->
            <div class="mt-12 pt-8 border-t border-gray-100">
                <a href="{{ route('artikel') }}" class="text-teal-600 font-semibold hover:underline">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Artikel
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Related Articles (Optional, if passed) -->
@if(isset($relatedArticles) && $relatedArticles->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-800 mb-8">Artikel Terkait</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($relatedArticles as $related)
            <div class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                @if($related->featured_image)
                <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-6">
                    <h3 class="font-bold text-gray-800 mb-2 line-clamp-2">
                        <a href="{{ route('artikel.detail', $related->slug) }}" class="hover:text-teal-600">{{ $related->title }}</a>
                    </h3>
                    <p class="text-gray-500 text-sm mb-4">{{ Str::limit($related->excerpt, 80) }}</p>
                    <a href="{{ route('artikel.detail', $related->slug) }}" class="text-teal-600 text-sm font-semibold hover:underline">Baca Selengkapnya</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
