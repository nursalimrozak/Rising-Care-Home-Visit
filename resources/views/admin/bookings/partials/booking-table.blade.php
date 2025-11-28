<div class="overflow-x-auto">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">No. Booking</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Layanan</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Jadwal</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tipe</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Petugas</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($bookings as $booking)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-mono text-sm text-gray-600">{{ $booking->booking_number }}</td>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">{{ $booking->customer?->name ?? 'N/A' }}</div>
                    <div class="text-xs text-gray-500">{{ $booking->customer?->email ?? '-' }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="font-medium text-gray-900">
                        @if($booking->packagePurchase)
                            Paket {{ $booking->packagePurchase->package->name }}
                        @else
                            Layanan Reguler
                        @endif
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $booking->booking_type == 'home_visit' ? 'Home Visit' : 'Klinik' }}
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-gray-900">{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('d M Y') }}</div>
                    <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($booking->scheduled_time)->format('H:i') }}</div>
                </td>
                <td class="px-6 py-4">
                    @if($booking->booking_type == 'home_visit')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Home Visit
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Klinik
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($booking->petugas)
                        <div class="text-sm text-gray-900">{{ $booking->petugas->name }}</div>
                    @else
                        <span class="text-xs text-gray-400 italic">Belum ditugaskan</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @php
                        $statusClasses = [
                            'scheduled' => 'bg-yellow-100 text-yellow-800',
                            'checked_in' => 'bg-blue-100 text-blue-800',
                            'in_progress' => 'bg-purple-100 text-purple-800',
                            'pending_payment' => 'bg-orange-100 text-orange-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'scheduled' => 'Terjadwal',
                            'checked_in' => 'Check-in',
                            'in_progress' => 'Diproses',
                            'pending_payment' => 'Menunggu Pembayaran',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];

                        $displayClass = $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800';
                        $displayLabel = $statusLabels[$booking->status] ?? ucfirst($booking->status);

                        if ($booking->status == 'pending_payment' && $booking->payment && $booking->payment->status == 'pending_verification') {
                            $displayLabel = 'Perlu Verifikasi';
                            $displayClass = 'bg-yellow-100 text-yellow-800';
                        }
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $displayClass }}">
                        {{ $displayLabel }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                        Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                    Belum ada booking yang tercatat
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="px-6 py-4 border-t border-gray-200">
    {{ $bookings->links() }}
</div>
