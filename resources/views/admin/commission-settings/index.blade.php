@extends('layouts.admin')

@section('title', 'Pengaturan Komisi - RisingCare')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Pengaturan Komisi</h1>
    <p class="text-gray-600">Atur persentase bagi hasil untuk setiap role</p>
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.commission-settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        @if($errors->has('total'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ $errors->first('total') }}
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Petugas -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Komisi Petugas (%)
                    <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="commission_petugas" 
                    value="{{ old('commission_petugas', $settings['commission_petugas']) }}" 
                    step="0.01"
                    min="0"
                    max="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('commission_petugas') border-red-500 @enderror"
                    required>
                @error('commission_petugas')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Persentase yang diterima petugas dari harga layanan</p>
            </div>

            <!-- Admin -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Komisi Admin Staff (%)
                    <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="commission_admin" 
                    value="{{ old('commission_admin', $settings['commission_admin']) }}" 
                    step="0.01"
                    min="0"
                    max="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('commission_admin') border-red-500 @enderror"
                    required>
                @error('commission_admin')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Persentase yang diterima admin staff + 100% revenue add-ons</p>
            </div>

            <!-- Superadmin -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Komisi Superadmin (%)
                    <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="commission_superadmin" 
                    value="{{ old('commission_superadmin', $settings['commission_superadmin']) }}" 
                    step="0.01"
                    min="0"
                    max="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('commission_superadmin') border-red-500 @enderror"
                    required>
                @error('commission_superadmin')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Persentase yang diterima superadmin dari harga layanan</p>
            </div>

            <!-- Service -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Komisi Service/System (%)
                    <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="commission_service" 
                    value="{{ old('commission_service', $settings['commission_service']) }}" 
                    step="0.01"
                    min="0"
                    max="100"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('commission_service') border-red-500 @enderror"
                    required>
                @error('commission_service')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-1">Persentase untuk operasional sistem</p>
            </div>
        </div>

        <!-- Total Display -->
        <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <span class="text-gray-700 font-medium">Total Persentase:</span>
                <span id="totalPercentage" class="text-2xl font-bold text-teal-600">
                    {{ $settings['commission_petugas'] + $settings['commission_admin'] + $settings['commission_superadmin'] + $settings['commission_service'] }}%
                </span>
            </div>
            <p class="text-sm text-gray-600 mt-2">
                <i class="fas fa-info-circle mr-1"></i>
                Total harus 100% untuk dapat menyimpan perubahan
            </p>
        </div>

        <!-- Important Notes -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-gray-800 mb-2">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                Catatan Penting
            </h3>
            <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                <li>Persentase dihitung dari <strong>harga layanan (service price)</strong> saja</li>
                <li><strong>Revenue add-ons 100%</strong> akan masuk ke Admin Staff</li>
                <li>Perubahan akan berlaku untuk transaksi baru setelah disimpan</li>
                <li>Total semua persentase harus <strong>tepat 100%</strong></li>
            </ul>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
                <i class="fas fa-save mr-2"></i>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

<script>
// Calculate total percentage in real-time
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('input', function() {
        const petugas = parseFloat(document.querySelector('[name="commission_petugas"]').value) || 0;
        const admin = parseFloat(document.querySelector('[name="commission_admin"]').value) || 0;
        const superadmin = parseFloat(document.querySelector('[name="commission_superadmin"]').value) || 0;
        const service = parseFloat(document.querySelector('[name="commission_service"]').value) || 0;
        
        const total = petugas + admin + superadmin + service;
        const totalEl = document.getElementById('totalPercentage');
        
        totalEl.textContent = total.toFixed(2) + '%';
        
        // Change color based on validity
        if (total === 100) {
            totalEl.classList.remove('text-red-600');
            totalEl.classList.add('text-teal-600');
        } else {
            totalEl.classList.remove('text-teal-600');
            totalEl.classList.add('text-red-600');
        }
    });
});
</script>
@endsection
