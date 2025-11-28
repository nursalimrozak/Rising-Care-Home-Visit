@extends('layouts.admin')

@section('title', 'Detail Booking - RisingCare')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Detail Booking</h1>
        <a href="{{ route('admin.bookings.index') }}" class="text-teal-600 hover:text-teal-800 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ showVerifyModal: false, showProofModal: false, proofImage: '' }">
    @php
        // Check if payment is fully settled (defined early for layout decision)
        $isFullyPaidAdmin = ($booking->payment && 
                            (($booking->payment->payment_type == 'dp' && $booking->payment->final_payment_proof && $booking->payment->status == 'paid') || 
                             ($booking->status == 'completed')));
    @endphp
    
    <!-- Main Booking Info (2 columns or full width if sidebar hidden) -->
    <div class="{{ $isFullyPaidAdmin ? 'lg:col-span-3' : 'lg:col-span-2' }} space-y-6">
        <!-- Booking Header -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Booking Information</h2>
                    <p class="text-sm text-gray-500 font-mono">{{ $booking->booking_number }}</p>
                </div>
                <div>
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

                        // Override if payment is pending verification
                        if ($booking->status == 'pending_payment' && $booking->payment && optional($booking->payment)->status == 'pending_verification') {
                            $displayLabel = 'Perlu Verifikasi';
                            $displayClass = 'bg-yellow-100 text-yellow-800';
                        }
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $displayClass }}">
                        {{ $displayLabel }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Customer</label>
                    <p class="font-medium text-gray-900">{{ $booking->customer->name }}</p>
                    <p class="text-sm text-gray-600">{{ $booking->customer->email }}</p>
                    <p class="text-sm text-gray-600">{{ $booking->customer->phone }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Layanan</label>
                    <p class="font-medium text-gray-900">{{ optional($booking->service)->name ?? 'Service Deleted' }}</p>
                    <p class="text-sm text-gray-600">{{ optional(optional($booking->service)->category)->name ?? '-' }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Jadwal</label>
                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('d F Y') }}</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($booking->scheduled_time)->format('H:i') }} WIB</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Tipe Kunjungan</label>
                    <p class="font-medium text-gray-900">
                        {{ $booking->booking_type == 'home_visit' ? 'Home Visit' : 'Klinik' }}
                    </p>
                </div>
            </div>

            @if($booking->booking_type == 'home_visit' && $booking->customer_address)
            <div class="mt-6 pt-6 border-t">
                <label class="text-sm text-gray-500 block mb-1">Alamat Kunjungan</label>
                <p class="text-gray-900">{{ $booking->customer_address }}</p>
            </div>
            @endif

            @if($booking->customer_notes)
            <div class="mt-6 pt-6 border-t">
                <label class="text-sm text-gray-500 block mb-1">Catatan Customer</label>
                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $booking->customer_notes }}</p>
            </div>
            @endif
        </div>

        <!-- Pricing -->
        <!-- Payment Details -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Rincian Pembayaran</h2>
            
            @php
                $servicePrice = $booking->estimated_price;
                $dpAmount = 0;
                $sisaBayarService = $servicePrice;
                
                // DP Calculation
                if($booking->payment && $booking->payment->payment_type == 'dp' && $booking->payment->paid_at) {
                    $dpAmount = $booking->payment->required_amount;
                    $sisaBayarService = $servicePrice - $dpAmount;
                }

                // Add-ons
                $addonsTotal = 0;
                if($booking->bookingAddons && $booking->bookingAddons->count() > 0) {
                    $addonsTotal = $booking->bookingAddons->sum('subtotal');
                }
                
                // Discounts
                $discounts = 0;
                if($booking->payment) {
                    if($booking->payment->membership_discount > 0) $discounts += $booking->payment->membership_discount;
                    if($booking->payment->voucher_discount > 0) $discounts += $booking->payment->voucher_discount;
                }
                
                // Final Calculation
                $totalKurangBayar = $sisaBayarService + $addonsTotal - $discounts;
                
                // For full payment scenarios (non-DP)
                $paidAmount = 0;
                if($booking->payment && $booking->payment->payment_type != 'dp' && $booking->payment->status == 'paid') {
                    $paidAmount = $booking->payment->total_amount;
                    $totalKurangBayar = 0; // Fully paid
                }
            @endphp

            <div class="space-y-3">
                <!-- Service Price -->
                <div class="flex justify-between text-gray-800">
                    <span>
                        @if($booking->packagePurchase)
                            Paket {{ $booking->packagePurchase->package->name }}
                        @else
                            {{ $booking->service->name ?? 'Layanan Tidak Tersedia' }}
                        @endif
                    </span>
                    <span class="font-medium">Rp {{ number_format($booking->estimated_price, 0, ',', '.') }}</span>
                </div>

                <!-- DP Display & Remaining Service Cost -->
                @if($dpAmount > 0)
                <div class="flex justify-between text-green-600 font-medium">
                    <span>Sudah Dibayar (DP)</span>
                    <span>Rp {{ number_format($dpAmount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-800 border-b border-gray-100 pb-2">
                    <span>Sisa Bayar Layanan</span>
                    <span>Rp {{ number_format($sisaBayarService, 0, ',', '.') }}</span>
                </div>
                @endif

                <!-- Add-ons -->
                @if($booking->bookingAddons && $booking->bookingAddons->count() > 0)
                    <div class="flex justify-between text-gray-800 pt-2">
                        <span>Add-ons</span>
                        <span class="font-medium">Rp {{ number_format($addonsTotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="space-y-1">
                        @foreach($booking->bookingAddons as $addon)
                        <div class="flex justify-between text-gray-500 text-sm pl-4 border-l-2 border-gray-200 ml-1">
                            <span>{{ $addon->addon->name }} (x{{ $addon->quantity }})</span>
                            <span>Rp {{ number_format($addon->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                @endif

                @if($booking->payment)
                    <!-- Discounts -->
                    @if($booking->payment->membership_discount > 0)
                    <div class="flex justify-between text-green-600 text-sm">
                        <span>Diskon Membership</span>
                        <span>-Rp {{ number_format($booking->payment->membership_discount, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    @if($booking->payment->voucher_discount > 0)
                    <div class="flex justify-between text-green-600 text-sm">
                        <span>Diskon Voucher</span>
                        <span>-Rp {{ number_format($booking->payment->voucher_discount, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <!-- Total Outstanding / Full Payment Status -->
                    @if($booking->payment->payment_type == 'dp')
                        <div class="border-t border-gray-200 pt-3 flex justify-between font-bold text-orange-600 text-lg">
                            <span>Total Kurang Bayar</span>
                            <span>Rp {{ number_format($totalKurangBayar, 0, ',', '.') }}</span>
                        </div>
                        
                        {{-- Show LUNAS badge if final payment verified --}}
                        @if($booking->payment->final_payment_proof && $booking->payment->status == 'paid')
                        <div class="mt-2 text-right">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">LUNAS</span>
                        </div>
                        @endif
                    @else
                        <!-- Full Payment Scenario -->
                        <div class="border-t border-gray-200 pt-3 flex justify-between font-bold text-gray-900 text-lg">
                            <span>Total Pembayaran</span>
                            <span>Rp {{ number_format($servicePrice + $addonsTotal - $discounts, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($paidAmount > 0)
                        <div class="flex justify-between text-green-600 font-medium pt-1">
                            <span>Sudah Dibayar (Full)</span>
                            <span>Rp {{ number_format($paidAmount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-green-600 text-lg pt-2">
                            <span>Status</span>
                            <span>LUNAS</span>
                        </div>
                        @endif
                    @endif
                @else
                    <!-- No Payment Record yet -->
                    <div class="border-t border-gray-200 pt-3 flex justify-between font-bold text-gray-900 text-lg">
                        <span>Estimasi Total</span>
                        <span>Rp {{ number_format($booking->estimated_price, 0, ',', '.') }}</span>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Sidebar Actions - hide completely if fully paid -->
    @if(!$isFullyPaidAdmin && $booking->payment)
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Verifikasi Pembayaran</h3>
            
            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Status Pembayaran</p>
                @if(optional($booking->payment)->status == 'paid')
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">LUNAS</span>
                @elseif(optional($booking->payment)->status == 'pending_verification')
                    <span class="px-3 py-1 bg-green-100 text-yellow-800 rounded-full text-xs font-bold">PERLU VERIFIKASI</span>
                @elseif(optional($booking->payment)->status == 'failed')
                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">DITOLAK</span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold">PENDING</span>
                @endif
            </div>

            <!-- Proof Buttons -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                @if(optional($booking->payment)->payment_proof)
                    @if(optional($booking->payment)->payment_type == 'dp')
                    <button @click="showProofModal = true; proofImage = '{{ asset('storage/' . $booking->payment->payment_proof) }}'" 
                        class="w-full bg-blue-50 text-blue-700 border border-blue-200 px-3 py-2 rounded-lg hover:bg-blue-100 transition font-medium flex items-center justify-center text-sm">
                        <i class="fas fa-receipt mr-2"></i> Bukti DP
                    </button>
                    @else
                    <button @click="showProofModal = true; proofImage = '{{ asset('storage/' . $booking->payment->payment_proof) }}'" 
                        class="w-full bg-blue-50 text-blue-700 border border-blue-200 px-3 py-2 rounded-lg hover:bg-blue-100 transition font-medium flex items-center justify-center text-sm">
                        <i class="fas fa-receipt mr-2"></i> Bukti Transfer
                    </button>
                    @endif
                @endif

                @if(optional($booking->payment)->final_payment_proof)
                <button @click="showProofModal = true; proofImage = '{{ asset('storage/' . $booking->payment->final_payment_proof) }}'" 
                    class="w-full bg-purple-50 text-purple-700 border border-purple-200 px-3 py-2 rounded-lg hover:bg-purple-100 transition font-medium flex items-center justify-center text-sm">
                    <i class="fas fa-receipt mr-2"></i> Bukti Pelunasan
                </button>
                @endif
            </div>

            @if(!$isFullyPaidAdmin && $booking->payment && in_array(optional($booking->payment)->status, ['pending', 'pending_verification']))
            <div class="flex flex-col gap-2">
                <form id="verify-form" action="{{ route('admin.payments.verify', $booking->payment) }}" method="POST">
                    @csrf
                    <button type="button" @click="showVerifyModal = true" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium">
                        <i class="fas fa-check mr-2"></i> Verifikasi
                    </button>
                </form>
                
                <button onclick="document.getElementById('reject-form').classList.toggle('hidden')" class="w-full bg-red-100 text-red-700 px-4 py-2 rounded-lg hover:bg-red-200 font-medium">
                    <i class="fas fa-times mr-2"></i> Tolak
                </button>
                
                <form id="reject-form" action="{{ route('admin.payments.reject', $booking->payment) }}" method="POST" class="hidden mt-2">
                    @csrf
                    <textarea name="rejection_reason" required placeholder="Alasan penolakan..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-2"></textarea>
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 text-sm">
                        Kirim Penolakan
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Assign Petugas -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Petugas</h3>
            @if($booking->petugas)
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Ditugaskan kepada:</p>
                    <p class="font-medium text-gray-900">{{ $booking->petugas->name }}</p>
                </div>
            @endif
            
            <form action="{{ route('admin.bookings.assign-petugas', $booking) }}" method="POST">
                @csrf
                <label class="block text-sm font-medium text-gray-700 mb-2">Tugaskan Petugas</label>
                <select name="petugas_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 mb-3 disabled:bg-gray-100 disabled:text-gray-500"
                    {{ $booking->status == 'pending_payment' ? 'disabled' : '' }}>
                    <option value="">Pilih Petugas</option>
                    @foreach($petugas as $p)
                        <option value="{{ $p->id }}" {{ $booking->petugas_id == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ $booking->status == 'pending_payment' ? 'disabled' : '' }}>
                    Tugaskan
                </button>
                @if($booking->status == 'pending_payment')
                    <p class="text-xs text-red-500 mt-2 italic">
                        <i class="fas fa-info-circle mr-1"></i> Verifikasi pembayaran terlebih dahulu sebelum menugaskan petugas.
                    </p>
                @endif
            </form>
        </div>

        <!-- Update Status -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Update Status</h3>
            <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                @csrf
                <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 mb-3">
                    <option value="scheduled" {{ $booking->status == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                    @if($booking->booking_type != 'home_visit')
                    <option value="checked_in" {{ $booking->status == 'checked_in' ? 'selected' : '' }}>Check-in</option>
                    @endif
                    <option value="in_progress" {{ $booking->status == 'in_progress' ? 'selected' : '' }}>Diproses</option>
                    <option value="pending_payment" {{ $booking->status == 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Update Status
                </button>
            </form>
        </div>
    </div>
    @endif

    <!-- Verification Modal -->
    <div x-show="showVerifyModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showVerifyModal = false" class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 transform transition-all">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Verifikasi Pembayaran?</h3>
                <p class="text-gray-500 mb-6">Apakah Anda yakin ingin memverifikasi pembayaran ini? Status booking akan diperbarui.</p>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 text-left rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Mohon periksa mutasi rekening Anda. Pastikan dana sejumlah <span class="font-bold">Rp {{ number_format($booking->final_price ?? $booking->estimated_price, 0, ',', '.') }}</span> sudah masuk sebelum memverifikasi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                <button @click="showVerifyModal = false" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition">
                    Batal
                </button>
                <button @click="document.getElementById('verify-form').submit()" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition">
                    Ya, Verifikasi
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Proof Modal -->
    <div x-show="showProofModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showProofModal = false" class="relative max-w-4xl w-full max-h-full overflow-auto bg-white rounded-lg shadow-2xl">
            <button @click="showProofModal = false" class="absolute top-2 right-2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-75 transition z-10">
                <i class="fas fa-times"></i>
            </button>
            <img :src="proofImage" alt="Bukti Transfer" class="w-full h-auto object-contain">
        </div>
    </div>
</div>
@endsection
