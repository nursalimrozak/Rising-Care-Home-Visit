@extends('layouts.admin')

@section('title', 'Payouts - RisingCare')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Payout</h1>
        <p class="text-gray-600">Riwayat pembayaran komisi mingguan</p>
    </div>
    <!-- Optional: Button to trigger manual payout generation if needed -->
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Periode</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Penerima</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total Komisi</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Biaya Admin</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Net Payout</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payouts as $payout)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $payout->period_start->format('d M') }} - {{ $payout->period_end->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $payout->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ ucfirst($payout->user->role) }}</div>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm text-gray-600">
                        Rp {{ number_format($payout->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 font-mono text-sm text-red-600">
                        - Rp {{ number_format($payout->fee, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 font-mono text-sm font-bold text-green-600">
                        Rp {{ number_format($payout->net_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($payout->status == 'processed')
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Processed
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.payouts.show', $payout) }}" class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        Belum ada data payout
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $payouts->links() }}
    </div>
</div>
@endsection
