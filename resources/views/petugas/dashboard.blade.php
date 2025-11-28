@extends('layouts.petugas')

@section('title', 'Dashboard Petugas - RisingCare')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Petugas</h1>
    <p class="text-gray-600">Selamat datang, {{ Auth::user()->name }}</p>
</div>

@if(!Auth::user()->payoutDetail)
    <!-- Notification moved to layout -->
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Total Booking Hari Ini -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Booking Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">{{ $todayBookings }}</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-calendar-day text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Booking Aktif -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Booking Aktif</p>
                <p class="text-2xl font-bold text-gray-900">{{ $activeBookings }}</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-tasks text-purple-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Selesai -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Selesai</p>
                <p class="text-2xl font-bold text-gray-900">{{ $completedBookings }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Booking Terbaru -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800">Booking Terbaru</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">No. Booking</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Layanan</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Jadwal</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentBookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-sm">
                        @php
                            // For package bookings, show parent (first) booking number
                            $displayNumber = $booking->booking_number; // Default
                            $visitLabel = '';
                            
                            if ($booking->package_purchase_id && $booking->packagePurchase && $booking->packagePurchase->bookings) {
                                // Get all bookings for this package, sorted by creation time
                                $allBookings = $booking->packagePurchase->bookings->sortBy('created_at');
                                $parentBooking = $allBookings->first();
                                
                                if ($parentBooking) {
                                    $displayNumber = $parentBooking->booking_number;
                                }
                                
                                // Calculate visit number based on position in sorted bookings
                                $visitNumber = $allBookings->search(function($b) use ($booking) {
                                    return $b->id === $booking->id;
                                }) + 1;
                                
                                if ($visitNumber >= 1) {
                                    $visitLabel = ' (Kunjungan #' . $visitNumber . ')';
                                }
                            }
                        @endphp
                        {{ $displayNumber }}
                        @if($visitLabel)
                            <span class="text-xs text-teal-600 ml-1">{{ $visitLabel }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($booking->customer)
                            <div class="font-medium text-gray-900">{{ $booking->customer->name }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->customer->phone }}</div>
                        @else
                            <div class="text-gray-500 italic">Customer tidak ditemukan</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-900">
                        @if($booking->service)
                            {{ $booking->service->name }}
                            @if($booking->packagePurchase && $booking->packagePurchase->package)
                                <span class="text-teal-600"> - {{ $booking->packagePurchase->package->name }}</span>
                            @endif
                        @else
                            Layanan tidak ditemukan
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-900">{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('d M Y') }}</div>
                        <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->scheduled_time)->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusClasses = [
                                'scheduled' => 'bg-yellow-100 text-yellow-800',
                                'checked_in' => 'bg-blue-100 text-blue-800',
                                'in_progress' => 'bg-purple-100 text-purple-800',
                                'pending_payment' => 'bg-orange-100 text-orange-800',
                                'completed' => 'bg-green-100 text-green-800',
                            ];
                            $statusLabels = [
                                'scheduled' => 'Terjadwal',
                                'checked_in' => 'Check-in',
                                'in_progress' => 'Diproses',
                                'pending_payment' => 'Menunggu Pembayaran',
                                'completed' => 'Selesai',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        Belum ada booking
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
