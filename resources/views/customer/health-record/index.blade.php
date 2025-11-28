@extends('layouts.customer')

@section('title', 'Rekap Kesehatan - RisingCare')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Rekap Kesehatan</h1>
        <p class="text-gray-600">Isi data kesehatan Anda untuk membantu kami memberikan layanan terbaik.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-8" x-data="{ 
        currentStep: 0, 
        totalSteps: {{ $categories->count() }},
        showSummary: {{ $hasCompleted ? 'true' : 'false' }}
    }">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Summary View -->
        <div x-show="showSummary" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Ringkasan Kesehatan Anda</h2>
                    @if($lastUpdated)
                        <p class="text-sm text-gray-500 mt-1">
                            Terakhir diperbarui: {{ \Carbon\Carbon::parse($lastUpdated)->translatedFormat('d F Y H:i') }}
                        </p>
                    @endif
                </div>
                <button @click="showSummary = false" class="bg-teal-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-teal-700 transition flex items-center gap-2">
                    <i class="fas fa-edit"></i> Edit Data
                </button>
            </div>

            <div class="space-y-8">
                @foreach($categories as $category)
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h3 class="text-lg font-bold text-teal-800 mb-4 border-b border-gray-200 pb-2">{{ $category->name }}</h3>
                        <div class="space-y-4">
                            @foreach($category->questions as $question)
                                <div>
                                    <p class="font-medium text-gray-700 mb-1">{{ $loop->iteration }}. {{ $question->question }}</p>
                                    <div class="text-gray-600 pl-4 border-l-2 border-teal-200">
                                        @php
                                            $response = $userResponses[$question->id]->response ?? '-';
                                            if ($question->type == 'checklist' && $response != '-') {
                                                $response = implode(', ', json_decode($response, true) ?? []);
                                            }
                                        @endphp
                                        {{ $response }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Wizard Form View -->
        <div x-show="!showSummary" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <!-- Progress Bar -->
            <div class="mb-8" x-show="totalSteps > 0">
                <div class="flex justify-between mb-2">
                    <span class="text-sm font-medium text-teal-600" x-text="`Langkah ${currentStep + 1} dari ${totalSteps}`"></span>
                    <span class="text-sm font-medium text-gray-500" x-text="Math.round(((currentStep + 1) / totalSteps) * 100) + '%'"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-teal-600 h-2.5 rounded-full transition-all duration-300" :style="`width: ${((currentStep + 1) / totalSteps) * 100}%`"></div>
                </div>
            </div>

            <form action="{{ route('customer.health-record.store') }}" method="POST">
                @csrf
                
                @forelse($categories as $index => $category)
                    <div x-show="currentStep === {{ $index }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                        <div class="mb-6">
                            <h2 class="text-xl font-bold text-gray-800">{{ $category->name }}</h2>
                            @if($category->description)
                                <p class="text-gray-600 mt-1">{{ $category->description }}</p>
                            @endif
                        </div>

                        <div class="space-y-6">
                            @forelse($category->questions as $question)
                                <div class="border-b border-gray-100 pb-6 last:border-0">
                                    <label class="block text-lg font-medium text-gray-800 mb-3">
                                        {{ $loop->iteration }}. {{ $question->question }} <span class="text-red-500">*</span>
                                    </label>
                                    
                                    @php
                                        $response = $userResponses[$question->id]->response ?? '';
                                    @endphp

                                    @if($question->type == 'text')
                                        <textarea name="question_{{ $question->id }}" rows="3" required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                            placeholder="Tulis jawaban Anda disini...">{{ $response }}</textarea>
                                    
                                    @elseif($question->type == 'boolean')
                                        <div class="flex gap-6">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" name="question_{{ $question->id }}" value="Ya" required
                                                    {{ $response == 'Ya' ? 'checked' : '' }}
                                                    class="w-5 h-5 text-teal-600 focus:ring-teal-500 border-gray-300">
                                                <span class="ml-2 text-gray-700">Ya</span>
                                            </label>
                                            <label class="flex items-center cursor-pointer">
                                                <input type="radio" name="question_{{ $question->id }}" value="Tidak" required
                                                    {{ $response == 'Tidak' ? 'checked' : '' }}
                                                    class="w-5 h-5 text-teal-600 focus:ring-teal-500 border-gray-300">
                                                <span class="ml-2 text-gray-700">Tidak</span>
                                            </label>
                                        </div>

                                    @elseif($question->type == 'checklist')
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @foreach($question->options as $option)
                                                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                                    <input type="checkbox" name="question_{{ $question->id }}[]" value="{{ $option }}"
                                                        {{ in_array($option, json_decode($response, true) ?? []) ? 'checked' : '' }}
                                                        class="w-5 h-5 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                                    <span class="ml-3 text-gray-700">{{ $option }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">* Pilih minimal satu opsi</p>

                                    @elseif($question->type == 'scale')
                                        <div class="flex items-center gap-4">
                                            <span class="text-sm text-gray-500">1 (Buruk)</span>
                                            <input type="range" name="question_{{ $question->id }}" min="1" max="10" value="{{ $response ?: 5 }}" required
                                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-teal-600"
                                                oninput="document.getElementById('val_{{ $question->id }}').innerText = this.value">
                                            <span class="text-sm text-gray-500">10 (Sangat Baik)</span>
                                        </div>
                                        <div class="text-center mt-2 font-bold text-teal-600 text-lg" id="val_{{ $question->id }}">
                                            {{ $response ?: 5 }}
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    Tidak ada pertanyaan dalam kategori ini.
                                </div>
                            @endforelse
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center">
                            <div>
                                <button type="button" x-show="currentStep > 0" @click="currentStep--" class="px-6 py-2 text-gray-600 hover:bg-gray-100 rounded-lg transition font-medium">
                                    <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                                </button>
                                <button type="button" x-show="currentStep === 0 && {{ $hasCompleted ? 'true' : 'false' }}" @click="showSummary = true" class="bg-red-100 text-red-700 px-6 py-2 rounded-lg font-bold hover:bg-red-200 transition shadow-sm">
                                    <i class="fas fa-times mr-2"></i> Batal
                                </button>
                            </div>
                            
                            <div class="flex gap-3">
                                <button type="button" x-show="currentStep < totalSteps - 1" @click="currentStep++" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition shadow-md">
                                    Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                                
                                <button type="submit" x-show="currentStep === totalSteps - 1" class="bg-teal-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-teal-700 transition shadow-lg">
                                    Simpan Rekap Kesehatan
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="mb-4">
                            <i class="fas fa-clipboard-list text-gray-300 text-5xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Belum Ada Pertanyaan</h3>
                        <p class="text-gray-500 mt-1">Saat ini belum ada pertanyaan rekap kesehatan yang tersedia.</p>
                    </div>
                @endforelse
            </form>
        </div>
    </div>
</div>
@endsection
