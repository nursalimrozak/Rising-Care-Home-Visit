@extends('layouts.customer')

@section('title', 'Paket Saya - RisingCare')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Paket Layanan Saya</h1>

    @if($packages->where('status', 'active')->count() > 0)
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Paket Aktif</h2>
        <div class="grid grid-cols-1 gap-6">
            @foreach($packages->where('status', 'active') as $package)
            <div class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-teal-500">
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-4 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg">{{ $package->package->name }}</h3>
                            <p class="text-sm text-teal-100">{{ $package->service->name }}</p>
                        </div>
                        <span class="px-3 py-1 bg-white text-teal-600 rounded-full text-xs font-bold">AKTIF</span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-2xl font-bold text-teal-600">{{ $package->getRemainingVisits() }}</p>
                            <p class="text-xs text-gray-600">Sisa Kunjungan</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <p class="text-2xl font-bold text-gray-800">{{ $package->total_visits }}</p>
                            <p class="text-xs text-gray-600">Total Kunjungan</p>
                        </div>
                    </div>
                    
                    @if($package->expires_at)
                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            Berlaku hingga: <strong>{{ $package->expires_at->format('d M Y') }}</strong>
                            <span class="text-xs">({{ $package->expires_at->diffForHumans() }})</span>
                        </p>
                    </div>
                    @endif
                    
                    <div>
                        <a href="{{ route('customer.packages.show', base64_encode($package->id)) }}" 
                           class="block w-full text-center px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($packages->whereIn('status', ['completed', 'expired', 'cancelled'])->count() > 0)
    <div>
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Riwayat Paket</h2>
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Layanan</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Kunjungan</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($packages->whereIn('status', ['completed', 'expired', 'cancelled']) as $package)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <span class="font-medium text-gray-900">{{ $package->package->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $package->service->name }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm text-gray-600">{{ $package->used_visits }}/{{ $package->total_visits }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($package->status == 'completed')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Selesai</span>
                            @elseif($package->status == 'cancelled')
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Dibatalkan</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">Kadaluarsa</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('customer.packages.show', base64_encode($package->id)) }}" class="text-teal-600 hover:text-teal-800 text-sm font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($packages->count() == 0)
    <div class="bg-white rounded-xl shadow-md p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-box-open text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Paket</h3>
        <p class="text-gray-500 mb-6">Anda belum memiliki paket layanan. Beli paket untuk mendapatkan harga lebih hemat!</p>
        <a href="{{ route('booking.create') }}" 
           class="inline-flex items-center px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-medium">
            <i class="fas fa-plus mr-2"></i> Buat Booking Baru
        </a>
    </div>
    @endif
</div>
@endsection
