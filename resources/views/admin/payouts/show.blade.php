@extends('layouts.admin')

@section('title', 'Detail Payout - RisingCare')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Detail Payout</h1>
        <a href="{{ route('admin.payouts.index') }}" class="text-teal-600 hover:text-teal-800 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Payout Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Payout</h2>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Penerima</label>
                    <p class="font-medium text-gray-900">{{ $payout->user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $payout->user->email }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Periode</label>
                    <p class="font-medium text-gray-900">
                        {{ $payout->period_start->format('d M Y') }} - {{ $payout->period_end->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Status</label>
                    @if($payout->status == 'processed')
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Processed
                        </span>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $payout->processed_at ? $payout->processed_at->format('d M Y H:i') : '-' }}
                        </p>
                    @else
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="font-medium text-gray-800 mb-3">Detail Rekening Penerima</h3>
                @if($payout->user->payoutDetail)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 block">Metode</span>
                                <span class="font-medium text-gray-900 uppercase">{{ $payout->user->payoutDetail->payment_type }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Provider</span>
                                <span class="font-medium text-gray-900">{{ $payout->user->payoutDetail->provider_name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Nomor Akun</span>
                                <span class="font-medium text-gray-900 font-mono">{{ $payout->user->payoutDetail->account_number }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 block">Atas Nama</span>
                                <span class="font-medium text-gray-900">{{ $payout->user->payoutDetail->account_holder_name }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Data Pembayaran Belum Lengkap</p>
                            <p class="text-xs text-yellow-700 mt-1">User ini belum melengkapi data rekening/e-wallet di profil mereka.</p>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="font-medium text-gray-800 mb-3">Rincian Keuangan</h3>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Komisi (Gross)</span>
                        <span class="font-medium">Rp {{ number_format($payout->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Biaya Admin (Fee)</span>
                        <span class="font-medium text-red-600">- Rp {{ number_format($payout->fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-100 flex justify-between text-base font-bold">
                        <span class="text-gray-900">Net Payout</span>
                        <span class="text-green-600">Rp {{ number_format($payout->net_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commissions List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Rincian Komisi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Sumber</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($payout->commissions as $commission)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $commission->created_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $commission->payment->payment_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ ucfirst($commission->role) }} ({{ $commission->percentage }}%)
                            </td>
                            <td class="px-6 py-4 font-mono text-sm font-medium text-gray-900">
                                Rp {{ number_format($commission->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Action / Proof -->
    <div class="space-y-6">
        @if($payout->status == 'pending')
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Proses Payout</h3>
            <form action="{{ route('admin.payouts.process', $payout) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer</label>
                        <input type="file" name="proof_file" required accept="image/*" class="w-full px-3 py-2 border rounded-lg text-sm">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg text-sm"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium">
                        <i class="fas fa-check-circle mr-2"></i> Tandai Sudah Ditransfer
                    </button>
                </div>
            </form>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Bukti Transfer</h3>
            @if($payout->proof_file)
                <img src="{{ asset('storage/' . $payout->proof_file) }}" alt="Bukti Transfer" class="w-full rounded-lg border border-gray-200 mb-4">
                <a href="{{ asset('storage/' . $payout->proof_file) }}" target="_blank" class="block text-center text-sm text-blue-600 hover:underline">
                    Lihat Ukuran Penuh
                </a>
            @else
                <p class="text-gray-500 italic text-sm">Tidak ada bukti transfer</p>
            @endif
            
            @if($payout->notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <label class="text-xs text-gray-500 uppercase font-bold">Catatan</label>
                <p class="text-sm text-gray-800 mt-1">{{ $payout->notes }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
