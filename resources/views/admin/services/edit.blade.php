@extends('layouts.admin')

@section('title', 'Edit Layanan - RisingCare')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.services.index') }}" class="bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Layanan</h1>
            <p class="text-gray-600">Perbarui detail layanan dan harga per membership</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-8 max-w-4xl">
    <form action="{{ route('admin.services.update', $service) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Basic Info -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Informasi Dasar</h3>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Nama Layanan</label>
                    <input type="text" name="name" value="{{ old('name', $service->name) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                    <select name="service_category_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('service_category_id', $service->service_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Durasi (Menit)</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $service->duration_minutes) }}" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('description', $service->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <span class="ml-2 text-gray-700">Layanan Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Pricing -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Harga & Membership</h3>
                
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Harga Dasar (Umum)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Rp</span>
                        <input type="number" name="base_price" value="{{ old('base_price', $service->base_price) }}" required min="0"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 font-bold text-gray-800">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Harga untuk customer tanpa membership atau default.</p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Harga Khusus Membership (Opsional)</p>
                    
                    @foreach($memberships as $membership)
                        <div class="mb-3">
                            <label class="block text-sm text-gray-600 mb-1">{{ $membership->name }}</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                <input type="number" name="prices[{{ $membership->id }}]" 
                                    value="{{ old('prices.'.$membership->id, $currentPrices[$membership->id] ?? '') }}" 
                                    min="0"
                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm"
                                    placeholder="Kosongkan untuk ikut harga dasar">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8 pt-6 border-t">
            <a href="{{ route('admin.services.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-300 transition">
                Batal
            </a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition">
                Perbarui Layanan
            </button>
        </div>
    </form>
</div>
@endsection
