@extends('layouts.admin')

@section('title', 'CTA Appointment Section - RisingCare')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">CTA Appointment Section</h1>
    <p class="text-gray-600">Kelola section Call-to-Action di landing page</p>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.landing.cta-appointment.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-medium mb-2">Section Title <span class="text-red-500">*</span></label>
                <input type="text" name="section_title" value="{{ old('section_title', $cta->section_title) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                @error('section_title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-medium mb-2">Section Subtitle</label>
                <textarea name="section_subtitle" rows="2"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">{{ old('section_subtitle', $cta->section_subtitle) }}</textarea>
                @error('section_subtitle')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Button Text <span class="text-red-500">*</span></label>
                <input type="text" name="button_text" value="{{ old('button_text', $cta->button_text) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                @error('button_text')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-2">Background Color</label>
                <input type="color" name="background_color" value="{{ old('background_color', $cta->background_color ?? '#0d9488') }}"
                    class="w-full h-10 px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Warna background section</p>
                @error('background_color')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-medium mb-2">Background Image (Opsional)</label>
                @if($cta->background_image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $cta->background_image) }}" alt="Background" class="w-full h-32 object-cover rounded-lg">
                    </div>
                @endif
                <input type="file" name="background_image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Upload gambar background (akan override warna background)</p>
                @error('background_image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $cta->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                    <span class="ml-2 text-gray-700">Aktif</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
            <button type="submit" class="bg-teal-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-teal-700 transition shadow-lg">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
