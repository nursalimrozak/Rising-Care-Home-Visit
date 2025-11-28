@extends('layouts.admin')

@section('title', 'Komisi - RisingCare')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Daftar Komisi</h1>
    <p class="text-gray-600">Riwayat pembagian komisi dari setiap transaksi</p>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Booking/Payment</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Penerima</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($commissions as $commission)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $commission->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $commission->payment->payment_number ?? '-' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            Booking: {{ $commission->payment->booking->booking_number ?? '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $commission->user->name ?? 'System (Service)' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ ucfirst($commission->role) }} ({{ $commission->percentage }}%)
                        </span>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm font-medium text-gray-900">
                        Rp {{ number_format($commission->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($commission->status == 'paid')
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Paid
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada data komisi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $commissions->links() }}
    </div>
</div>
@endsection
