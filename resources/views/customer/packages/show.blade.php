@extends('layouts.customer')

@section('title', 'Detail Paket - RisingCare')

@section('content')
<div class="container mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Detail Paket</h1>
        <a href="{{ route('customer.packages.index') }}" class="text-teal-600 hover:text-teal-800 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold">{{ $packagePurchase->package->name }}</h2>
                    <p class="text-teal-100 mt-1">{{ $packagePurchase->service->name }}</p>
                </div>
                <span class="px-4 py-2 bg-white text-teal-600 rounded-full text-sm font-bold">
                    {{ ucfirst($packagePurchase->status) }}
                </span>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 p-6 border-b">
            @php
                // Calculate used visits dynamically based on completed treatments only
                // pending_payment means treatment is done but not yet finalized by petugas
                $realUsedVisits = $packagePurchase->bookings->where('status', 'completed')->count();
                $remainingVisits = $packagePurchase->total_visits - $realUsedVisits;
            @endphp
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-3xl font-bold text-teal-600">{{ $remainingVisits }}</p>
                <p class="text-sm text-gray-600 mt-1">Sisa Kunjungan</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-3xl font-bold text-gray-800">{{ $realUsedVisits }}</p>
                <p class="text-sm text-gray-600 mt-1">Sudah Digunakan</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-3xl font-bold text-gray-800">{{ $packagePurchase->total_visits }}</p>
                <p class="text-sm text-gray-600 mt-1">Total Kunjungan</p>
            </div>
        </div>

        <!-- Details -->
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Paket</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Tanggal Pembelian</span>
                    <span class="font-medium text-gray-900">{{ $packagePurchase->purchased_at->format('d M Y, H:i') }}</span>
                </div>
                @if($packagePurchase->expires_at)
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Berlaku Hingga</span>
                    <span class="font-medium text-gray-900">{{ $packagePurchase->expires_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Sisa Waktu</span>
                    <span class="font-medium {{ $packagePurchase->isExpired() ? 'text-red-600' : 'text-gray-900' }}">
                        @if($packagePurchase->isExpired())
                            Sudah berakhir
                        @else
                            {{ ceil($packagePurchase->expires_at->diffInDays(now(), false)) }} hari lagi
                        @endif
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Bookings History -->
        <div class="p-6 border-t">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Kunjungan Treatment</h3>
            @if($packagePurchase->bookings->count() > 0)
            <div class="space-y-3">
                @php
                    // Find assigned staff from any booking in this package
                    $assignedStaff = $packagePurchase->bookings->whereNotNull('petugas_id')->first()->petugas ?? null;
                @endphp
                @foreach($packagePurchase->bookings->sortBy('scheduled_date') as $booking)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                            <span class="font-bold text-teal-600">{{ $loop->iteration }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($booking->scheduled_time)->format('H:i') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                @if($booking->petugas)
                                    Petugas: {{ $booking->petugas->name }}
                                @elseif($assignedStaff)
                                    Petugas: {{ $assignedStaff->name }} (Terjadwal)
                                @else
                                    Menunggu penugasan petugas
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $statusClasses = [
                                'scheduled' => 'bg-yellow-100 text-yellow-800',
                                'checked_in' => 'bg-blue-100 text-blue-800',
                                'in_progress' => 'bg-purple-100 text-purple-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'scheduled' => 'Terjadwal',
                                'checked_in' => 'Check-in',
                                'in_progress' => 'Diproses',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                        </span>
                        <a href="{{ route('customer.treatment.show', $booking->booking_number) }}" class="text-teal-600 hover:text-teal-800 text-sm font-medium">
                            Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-8">Belum ada kunjungan yang dijadwalkan</p>
            @endif
        </div>

        <!-- Actions -->
        @php
            $isExpired = $packagePurchase->isExpired();
            // Count active bookings (scheduled, not cancelled)
            $scheduledBookingsCount = $packagePurchase->bookings->whereNotIn('status', ['cancelled'])->count();
            $allVisitsScheduled = $scheduledBookingsCount >= $packagePurchase->total_visits;
            
            // Determine button state and message
            if ($isExpired) {
                $buttonText = 'Paket Sudah Kadaluarsa';
                $buttonDisabled = true;
            } elseif ($allVisitsScheduled) {
                $buttonText = 'Semua Kunjungan Sudah Dijadwalkan';
                $buttonDisabled = true;
            } else {
                $buttonText = 'Jadwalkan Kunjungan Berikutnya';
                $buttonDisabled = false;
            }
        @endphp
        
        <div class="p-6 bg-gray-50 border-t">
            @if($buttonDisabled)
                <button disabled class="block w-full text-center px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium opacity-60">
                    <i class="fas fa-ban mr-2"></i> {{ $buttonText }}
                </button>
            @else
                <a href="{{ route('booking.create', ['use_package' => base64_encode($packagePurchase->id)]) }}" 
                   class="block w-full text-center px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-medium">
                    <i class="fas fa-calendar-plus mr-2"></i> {{ $buttonText }}
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
