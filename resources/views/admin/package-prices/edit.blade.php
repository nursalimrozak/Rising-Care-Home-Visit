@extends('layouts.admin')

@section('title', 'Atur Harga Paket - ' . $service->name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Atur Harga Paket</h1>
        <p class="text-gray-600 mt-1">{{ $service->name }} - {{ $service->category->name }}</p>
    </div>
    <a href="{{ route('admin.package-prices.index') }}" class="text-teal-600 hover:text-teal-800 font-medium flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

@if($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.package-prices.update', $service) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-6">
                Atur harga untuk setiap kombinasi paket dan membership. Harga yang lebih tinggi untuk paket multi-visit biasanya memberikan diskon per kunjungan.
            </p>
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="border border-gray-200 px-4 py-3 text-left font-semibold text-gray-700">Paket</th>
                            @foreach($memberships as $membership)
                            <th class="border border-gray-200 px-4 py-3 text-center font-semibold text-gray-700">
                                {{ $membership->name }}
                                <div class="text-xs font-normal text-gray-500">Level {{ $membership->level }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($packages as $package)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-200 px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $package->name }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $package->visit_count }}x kunjungan
                                    @if($package->validity_weeks > 0)
                                        dalam {{ $package->validity_weeks }} minggu
                                    @endif
                                </div>
                            </td>
                            @foreach($memberships as $membership)
                            @php
                                $key = $package->id . '_' . $membership->id;
                                $existingPrice = $existingPrices->get($key)?->first()?->price ?? '';
                            @endphp
                            <td class="border border-gray-200 px-4 py-3">
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                    <input 
                                        type="number" 
                                        name="prices[{{ $package->id }}][{{ $membership->id }}]" 
                                        value="{{ old('prices.' . $package->id . '.' . $membership->id, $existingPrice) }}"
                                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                                        placeholder="0"
                                        min="0"
                                        step="1000"
                                        required
                                    >
                                </div>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
            <a href="{{ route('admin.package-prices.index') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition font-medium">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-medium">
                <i class="fas fa-save mr-2"></i> Simpan Harga
            </button>
        </div>
    </div>
</form>

<div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
    <div class="flex">
        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
        <div class="text-sm text-blue-700">
            <p class="font-semibold mb-1">Tips Penetapan Harga:</p>
            <ul class="list-disc list-inside space-y-1">
                <li>Paket multi-visit biasanya memberikan harga per kunjungan yang lebih murah</li>
                <li>Membership level lebih tinggi mendapat harga lebih baik</li>
                <li>Contoh: Reguler Rp 100.000, Eksekutif (4x) Rp 360.000 = Rp 90.000/kunjungan</li>
            </ul>
        </div>
    </div>
</div>
@endsection
