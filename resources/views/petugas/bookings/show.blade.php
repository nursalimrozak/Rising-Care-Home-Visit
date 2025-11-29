@extends('layouts.petugas')

@section('title', 'Detail Booking - RisingCare')

@section('content')
<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Detail Booking</h1>
        <a href="{{ route('petugas.bookings') }}" class="text-teal-600 hover:text-teal-800 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @php
        // Check if payment is fully settled (defined early for layout decision)
        $isFullyPaid = ($booking->payment && 
                       (($booking->payment->payment_type == 'dp' && $booking->payment->final_payment_proof && $booking->payment->status == 'paid') || 
                        ($booking->status == 'completed')));
    @endphp
    
    <!-- Main Content (Left - 2 columns or full width if sidebar hidden) -->
    <div class="{{ $isFullyPaid ? 'lg:col-span-3' : 'lg:col-span-2' }} space-y-6">
        <!-- Booking Info Card -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Informasi Booking</h2>
                    <p class="text-sm text-gray-500 font-mono">
                        @php
                            // For package bookings, show parent (first) booking number
                            $displayNumber = $booking->booking_number;
                            $visitLabel = '';
                            
                            if ($booking->package_purchase_id && $booking->packagePurchase && $booking->packagePurchase->bookings) {
                                $allBookings = $booking->packagePurchase->bookings->sortBy('created_at');
                                $parentBooking = $allBookings->first();
                                
                                if ($parentBooking) {
                                    $displayNumber = $parentBooking->booking_number;
                                }
                                
                                // Calculate visit number
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
                            <span class="text-teal-600">{{ $visitLabel }}</span>
                        @endif
                    </p>
                </div>
                <div>
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

                        // Override for pending_payment if package is paid and no addons
                        $showReadyToComplete = false;
                        if ($booking->status == 'pending_payment') {
                            $isPackagePaid = $booking->packagePurchase && in_array($booking->packagePurchase->status, ['active', 'completed']);
                            $hasAddons = $booking->bookingAddons->count() > 0;
                            
                            if ($isPackagePaid && !$hasAddons) {
                                $showReadyToComplete = true;
                            }
                        }
                    @endphp
                    
                    @if($showReadyToComplete)
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800">
                            Siap Diselesaikan
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Timer Display -->
            @if($booking->status == 'in_progress' && $booking->started_at)
            <div class="mb-6 p-4 bg-purple-50 border-2 border-purple-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-purple-600 text-2xl mr-3"></i>
                        <div>
                            <p class="text-sm text-purple-600 font-medium">Durasi Treatment</p>
                            <p id="timer" class="text-3xl font-bold text-purple-900">00:00:00</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Dimulai</p>
                        <p class="text-sm font-medium">{{ $booking->started_at->format('H:i') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Duration (if completed) -->
            @if($booking->duration_minutes)
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-center">
                <p class="text-sm text-green-600">Total Durasi Treatment</p>
                @php
                    $duration = abs($booking->duration_minutes);
                    $hours = floor($duration / 60);
                    $minutes = $duration % 60;
                @endphp
                <p class="text-2xl font-bold text-green-900">
                    @if($hours > 0)
                        {{ $hours }} jam {{ $minutes }} menit
                    @else
                        {{ $minutes }} menit
                    @endif
                </p>
            </div>
            @endif

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Customer</label>
                    <a href="{{ route('petugas.customers.show', $booking->customer->code) }}" class="font-medium text-teal-600 hover:text-teal-800 hover:underline">
                        {{ $booking->customer->name }}
                    </a>
                    <p class="text-sm text-gray-600">{{ $booking->customer->phone }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Layanan</label>
                    <p class="font-medium text-gray-900">{{ $booking->service->name }}</p>
                    <p class="text-sm text-gray-600">Rp {{ number_format($booking->estimated_price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Jadwal</label>
                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('d F Y') }}</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($booking->scheduled_time)->format('H:i') }} WIB</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500 block mb-1">Alamat</label>
                    <p class="text-sm text-gray-900">{{ $booking->customer_address ?? '-' }}</p>
                </div>
            </div>
            @if($booking->customer_notes)
            <div class="mt-4 pt-4 border-t">
                <label class="text-sm text-gray-500 block mb-1">Catatan Customer</label>
                <p class="text-sm text-gray-900 italic">"{{ $booking->customer_notes }}"</p>
            </div>
            @endif
        </div>

        <!-- Payment Breakdown -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h2>
            
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>
                            @if($booking->packagePurchase)
                                Paket {{ $booking->packagePurchase->package->name }}
                            @else
                                {{ $booking->service->name ?? 'Layanan Tidak Tersedia' }}
                            @endif
                        </span>
                        <span>Rp {{ number_format($booking->estimated_price, 0, ',', '.') }}</span>
                    </div>
                    @if($booking->bookingAddons->count() > 0)
                    <div class="flex justify-between text-sm">
                        <span>Add-ons</span>
                        <span>Rp {{ number_format($booking->bookingAddons->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    <div class="pl-4 text-xs text-gray-500 space-y-1 mt-1 mb-2 border-l-2 border-gray-200 ml-1">
                        @foreach($booking->bookingAddons as $addon)
                        <div class="flex justify-between">
                            <span>{{ $addon->addon->name }} (x{{ $addon->quantity }})</span>
                            <span>Rp {{ number_format($addon->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    @php
                        // For package bookings, check if package is already paid
                        $isPackageBooking = $booking->packagePurchase !== null;
                        // Package is paid if status is 'active' or 'completed' (not 'pending')
                        $isPackagePaid = $isPackageBooking && in_array($booking->packagePurchase->status, ['active', 'completed']);
                        
                        // Calculate total amount
                        if ($isPackagePaid) {
                            // Package already paid, only add-ons need payment
                            $totalAmount = $booking->bookingAddons->sum('subtotal');
                        } else {
                            // Regular booking or first package booking (pending payment)
                            $totalAmount = $booking->estimated_price + $booking->bookingAddons->sum('subtotal');
                        }
                        
                        // Calculate paid amount based on payment type
                        $paidAmount = 0;
                        if ($booking->payment) {
                            if ($booking->payment->payment_type == 'dp') {
                                $paidAmount = $booking->payment->required_amount;
                            } else {
                                $paidAmount = $booking->payment->total_amount;
                            }
                        }
                        
                        $remainingAmount = $totalAmount - $paidAmount;
                    @endphp
                    
                    @if($isPackagePaid)
                        {{-- Package is already paid, show only add-ons --}}
                        <div class="pt-2 border-t border-gray-300">
                            <div class="flex justify-between text-sm text-green-600 mb-2">
                                <span><i class="fas fa-check-circle mr-1"></i> Paket Sudah Dibayar</span>
                                <span class="font-medium">Rp {{ number_format($booking->estimated_price, 0, ',', '.') }}</span>
                            </div>
                            
                            @if($booking->bookingAddons->count() > 0)
                                <div class="flex justify-between text-sm">
                                    <span>Add-ons yang perlu dibayar:</span>
                                    <span class="font-medium">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">Tidak ada add-ons</p>
                            @endif
                        </div>
                    @else
                        {{-- Regular booking or first package booking --}}
                        <div class="pt-2 border-t border-gray-300 flex justify-between font-bold text-base">
                            <span>Total Pembayaran</span>
                            <span class="text-gray-900">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($booking->payment)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Sudah Dibayar ({{ $booking->payment->payment_type == 'dp' ? 'DP' : 'Lunas' }})</span>
                            <span class="font-medium">Rp {{ number_format($paidAmount, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($remainingAmount > 0)
                        <div class="pt-2 border-t border-gray-300 flex justify-between font-bold text-lg">
                            <span class="text-orange-600">Sisa Pembayaran</span>
                            <span class="text-orange-600">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</span>
                        </div>
                        
                        <p class="text-xs text-gray-500 italic mt-2">* Sisa pembayaran dibayar setelah treatment selesai</p>
                        @else
                        <div class="pt-2 border-t border-gray-300 flex justify-between font-bold text-lg">
                            <span class="text-green-600">Lunas</span>
                            <span class="text-green-600"><i class="fas fa-check-circle"></i></span>
                        </div>
                        @endif
                        @endif
                    @endif
                </div>
            </div>

            {{-- Show payment form only if: 
                1. No payment exists yet, OR
                2. Package is paid but there are unpaid add-ons
            --}}
            @if(!$booking->payment && (!$isPackagePaid || ($isPackagePaid && $totalAmount > 0)))
            <form action="{{ route('petugas.bookings.payment', $booking) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <select name="payment_method" id="paymentMethod" required class="w-full px-3 py-2 border rounded-lg">
                            <option value="">-- Pilih Metode --</option>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <div id="proofUpload" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer</label>
                        <input type="file" name="proof_image" accept="image/*" class="w-full px-3 py-2 border rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB</p>
                    </div>

                    <button type="submit" id="paymentSubmitBtn" disabled class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <i class="fas fa-check mr-2"></i> Proses Pembayaran
                    </button>
                </div>
            </form>
            @elseif($booking->payment)
            <!-- Payment Status -->
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Metode:</span>
                    <span class="font-medium">{{ $booking->payment->payment_method == 'cash' ? 'Cash' : 'Transfer' }}</span>
                </div>
                
                <div class="flex justify-between items-center gap-3">
                    <span class="text-gray-600">Status:</span>
                    <div class="flex items-center gap-2 flex-wrap justify-end">
                        @if($booking->payment->payment_proof)
                            @if($booking->payment->payment_type == 'dp')
                            <span onclick="openImageModal('{{ asset('storage/' . $booking->payment->payment_proof) }}')" 
                                class="cursor-pointer px-3 py-1.5 bg-blue-100 text-blue-800 rounded-lg text-xs font-medium hover:bg-blue-200 transition flex items-center gap-1 border border-blue-200">
                                <i class="fas fa-receipt"></i> Bukti DP Paket
                            </span>
                            @elseif($booking->payment->payment_type == 'addon')
                            <span onclick="openImageModal('{{ asset('storage/' . $booking->payment->payment_proof) }}')" 
                                class="cursor-pointer px-3 py-1.5 bg-teal-100 text-teal-800 rounded-lg text-xs font-medium hover:bg-teal-200 transition flex items-center gap-1 border border-teal-200">
                                <i class="fas fa-receipt"></i> Bukti Pembayaran Addons
                            </span>
                            @else
                            <span onclick="openImageModal('{{ asset('storage/' . $booking->payment->payment_proof) }}')" 
                                class="cursor-pointer px-3 py-1.5 bg-blue-100 text-blue-800 rounded-lg text-xs font-medium hover:bg-blue-200 transition flex items-center gap-1 border border-blue-200">
                                <i class="fas fa-receipt"></i> Bukti Pembayaran Paket
                            </span>
                            @endif
                        @endif

                        @if($booking->payment->final_payment_proof)
                        <span onclick="openImageModal('{{ asset('storage/' . $booking->payment->final_payment_proof) }}')" 
                            class="cursor-pointer px-3 py-1.5 bg-purple-100 text-purple-800 rounded-lg text-xs font-medium hover:bg-purple-200 transition flex items-center gap-1 border border-purple-200">
                            <i class="fas fa-receipt"></i> Bukti Pelunasan Paket
                        </span>
                        @endif
                        
                        {{-- Addon payment proof (for non-regular packages where addons are paid separately) --}}
                        @if($booking->payment->addon_payment_proof)
                        <span onclick="openImageModal('{{ asset('storage/' . $booking->payment->addon_payment_proof) }}')" 
                            class="cursor-pointer px-3 py-1.5 bg-teal-100 text-teal-800 rounded-lg text-xs font-medium hover:bg-teal-200 transition flex items-center gap-1 border border-teal-200">
                            <i class="fas fa-receipt"></i> Bukti Pembayaran Addons
                        </span>
                        @endif
                        
                        @if($booking->payment->status == 'paid')
                            @if($booking->payment->payment_type == 'dp' && $remainingAmount > 0)
                                <span class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium whitespace-nowrap border border-blue-100">DP Terbayar</span>
                            @else
                                <span class="px-3 py-1.5 bg-green-50 text-green-600 rounded-lg text-sm font-medium whitespace-nowrap border border-green-100">Lunas</span>
                            @endif
                        @elseif($booking->payment->status == 'pending_verification')
                            <span class="px-3 py-1.5 bg-orange-50 text-orange-600 rounded-lg text-sm font-medium whitespace-nowrap border border-orange-100">Menunggu Verifikasi</span>
                        @endif
                        
                        {{-- Show LUNAS badge if final payment verified --}}
                        @if($booking->payment->payment_type == 'dp' && $booking->payment->final_payment_proof && $booking->payment->status == 'paid')
                            <span class="px-3 py-1.5 bg-green-100 text-green-800 rounded-lg text-xs font-bold border border-green-200">LUNAS</span>
                        @endif
                    </div>
                </div>

                @if($booking->payment->status == 'pending')
                <div class="p-3 bg-yellow-50 text-yellow-800 rounded-lg text-sm">
                    <i class="fas fa-info-circle mr-1"></i> Menunggu verifikasi admin
                </div>
                @endif
            </div>
            @endif
            
            {{-- Upload Addon Payment Proof Form --}}
            @if($booking->bookingAddons->count() > 0 && (!$booking->payment || (!$booking->payment->addon_payment_proof && $booking->payment->payment_type != 'addon')))
            <div class="mt-4 p-4 bg-teal-50 border border-teal-200 rounded-lg" x-data="{ method: '' }">
                <h4 class="font-semibold text-teal-800 mb-3 flex items-center">
                    <i class="fas fa-upload mr-2"></i> Upload Bukti Pembayaran Add-ons
                </h4>
                <p class="text-sm text-teal-700 mb-3">
                    Total Add-ons: <span class="font-bold">Rp {{ number_format($booking->bookingAddons->sum('subtotal'), 0, ',', '.') }}</span>
                </p>
                <form action="{{ route('petugas.bookings.upload-addon-payment', $booking) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-3">
                        <!-- Bank Accounts (Visible when Transfer is selected) -->
                        <div x-show="method === 'transfer'" x-transition class="bg-white p-3 rounded-lg border border-teal-100 mb-3">
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Rekening Tujuan</p>
                            <div class="space-y-2">
                                @foreach($bankAccounts as $bank)
                                <div class="flex items-center gap-3 p-2 bg-gray-50 rounded border border-gray-100">
                                    <div class="bg-white p-1.5 rounded border">
                                        <i class="fas fa-university text-gray-600 text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-800 text-sm truncate">{{ $bank->bank_name }}</p>
                                        <div class="flex items-center gap-2">
                                            <p class="font-mono text-xs text-gray-600">{{ $bank->account_number }}</p>
                                            <button type="button" onclick="navigator.clipboard.writeText('{{ $bank->account_number }}')" class="text-xs text-teal-600 hover:text-teal-800">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                            <select name="payment_method" id="addonPaymentMethod" x-model="method" required class="w-full px-3 py-2 border rounded-lg">
                                <option value="">-- Pilih Metode --</option>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        
                        <div x-show="method === 'transfer'" class="hidden" :class="{ 'hidden': method !== 'transfer' }">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Transfer</label>
                            <input type="file" name="addon_proof_image" accept="image/*" class="w-full px-3 py-2 border rounded-lg">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB</p>
                        </div>
                        
                        <button type="submit" :disabled="!method" 
                            class="w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 font-medium disabled:bg-gray-400 disabled:cursor-not-allowed">
                            <i class="fas fa-check mr-2"></i> Proses Pembayaran Add-ons
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
        
        <!-- Action Buttons -->
        @if($booking->booking_type == 'on_site' && $booking->status == 'scheduled')
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Check-in Customer</h3>
            <form action="{{ route('petugas.bookings.checkin', $booking) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-check-circle mr-2"></i> Check-in
                </button>
            </form>
        </div>
        @endif

        @if(in_array($booking->status, ['scheduled', 'checked_in']))
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Mulai Treatment</h3>
            
            @php
                // Check if previous visit is completed (for package bookings)
                $canStart = true;
                $blockMessage = '';
                
                if ($booking->package_purchase_id && $booking->packagePurchase) {
                    $allBookings = $booking->packagePurchase->bookings->sortBy('created_at');
                    $currentIndex = $allBookings->search(function($b) use ($booking) {
                        return $b->id === $booking->id;
                    });
                    
                    if ($currentIndex > 0) {
                        $previousBooking = $allBookings[$currentIndex - 1];
                        if (!in_array($previousBooking->status, ['completed', 'pending_payment'])) {
                            $canStart = false;
                            $blockMessage = 'Kunjungan sebelumnya (#' . $currentIndex . ') harus diselesaikan terlebih dahulu.';
                        }
                    }
                }
            @endphp
            
            @if(!$canStart)
                <div class="mb-3 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                    <p class="text-sm text-orange-800">
                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $blockMessage }}
                    </p>
                </div>
            @endif
            
            <form action="{{ route('petugas.bookings.start', $booking) }}" method="POST">
                @csrf
                <button type="submit" 
                    class="w-full px-4 py-2 rounded-lg {{ $canStart ? 'bg-purple-600 hover:bg-purple-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                    {{ !$canStart ? 'disabled' : '' }}>
                    <i class="fas fa-play mr-2"></i> Mulai
                </button>
            </form>
        </div>
        @endif

        @if($booking->status == 'in_progress')
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Selesaikan Treatment</h3>
            <form action="{{ route('petugas.bookings.complete', $booking) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-stop mr-2"></i> Selesai
                </button>
            </form>
        </div>
        @endif

        <!-- Finalize Button (Main Content) -->
        @if($booking->status == 'pending_payment' || ($booking->payment && $booking->status != 'completed'))
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Penyelesaian</h3>
            
            @php
                $canFinalize = false;
                $statusMessage = '';
                $statusColor = 'green';

                // 1. Check if package booking
                if ($booking->packagePurchase && in_array($booking->packagePurchase->status, ['active', 'completed'])) {
                    // Package is paid, now check add-ons
                    $unpaidAddons = $booking->bookingAddons->sum('subtotal');
                    
                    if ($unpaidAddons > 0) {
                        // If has add-ons, check if they are paid (must be 'paid', not just 'pending_verification')
                        if ($booking->payment && $booking->payment->status === 'paid' && $booking->payment->payment_type === 'addon') {
                            $canFinalize = true;
                            $statusMessage = 'Pembayaran add-ons sudah lunas. Silakan selesaikan treatment.';
                        } elseif ($booking->payment && $booking->payment->status === 'pending_verification' && $booking->payment->payment_type === 'addon') {
                            $canFinalize = false;
                            $statusMessage = 'Pembayaran add-ons menunggu verifikasi admin.';
                            $statusColor = 'orange';
                        } else {
                            $canFinalize = false;
                            $statusMessage = 'Harap selesaikan pembayaran add-ons terlebih dahulu.';
                            $statusColor = 'orange';
                        }
                    } else {
                        // No add-ons, package is paid -> OK
                        $canFinalize = true;
                        $statusMessage = 'Paket sudah lunas. Silakan selesaikan treatment.';
                    }
                } 
                // 2. Regular booking or first package visit with payment
                elseif ($booking->payment && $booking->payment->status === 'paid') {
                    if ($booking->payment->payment_type === 'full') {
                        $canFinalize = true;
                        $statusMessage = 'Pembayaran lunas. Silakan selesaikan treatment.';
                    } elseif ($booking->payment->payment_type === 'dp' && $booking->payment->final_payment_proof) {
                        $canFinalize = true;
                        $statusMessage = 'Bukti pelunasan sudah diupload. Silakan selesaikan treatment.';
                    } else {
                        $canFinalize = false;
                        $statusMessage = 'Menunggu pelunasan pembayaran.';
                        $statusColor = 'orange';
                    }
                } else {
                    $canFinalize = false;
                    $statusMessage = 'Menunggu pembayaran...';
                    $statusColor = 'orange';
                }
            @endphp

            <div class="p-4 bg-{{ $statusColor }}-50 text-{{ $statusColor }}-800 rounded-lg mb-4 text-sm flex items-center">
                @if($statusColor == 'orange')
                    <i class="fas fa-exclamation-circle mr-2"></i>
                @else
                    <i class="fas fa-check-circle mr-2"></i>
                @endif
                {{ $statusMessage ?: 'Menunggu pembayaran...' }}
            </div>

            <form action="{{ route('petugas.bookings.finalize', $booking) }}" method="POST">
                @csrf
                <button type="submit" 
                    class="w-full px-4 py-2 rounded-lg font-bold text-lg shadow-lg transform transition hover:-translate-y-0.5 {{ $canFinalize ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                    {{ !$canFinalize ? 'disabled' : '' }}>
                    <i class="fas fa-check-double mr-2"></i> SELESAIKAN TREATMENT
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Right Sidebar (1 column) - hide completely if fully paid -->
    @if(!$isFullyPaid)
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Add-ons Layanan</h3>
            
            @if($booking->bookingAddons->count() > 0)
            <div class="space-y-2 mb-4">
                @foreach($booking->bookingAddons as $bookingAddon)
                <div class="p-3 bg-gray-50 rounded">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-gray-900 font-medium text-sm">{{ $bookingAddon->addon->name }}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500">x{{ $bookingAddon->quantity }}</span>
                            
                            @if($booking->status == 'pending_payment' || ($booking->payment && $booking->payment->status == 'paid' && $booking->payment->payment_type == 'dp'))
                            <button type="button" onclick="openGlobalDeleteModal('deleteAddonForm{{ $bookingAddon->id }}', '{{ addslashes($bookingAddon->addon->name) }}', 'Hapus Add-on')" class="text-red-500 hover:text-red-700 text-xs" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="deleteAddonForm{{ $bookingAddon->id }}" action="{{ route('petugas.booking-addons.destroy', $bookingAddon->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">@ Rp {{ number_format($bookingAddon->price_per_item, 0, ',', '.') }}</span>
                        <span class="text-teal-600 font-medium text-sm">Rp {{ number_format($bookingAddon->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
                <div class="pt-2 border-t">
                    <div class="flex justify-between items-center font-bold text-sm">
                        <span>Total:</span>
                        <span class="text-teal-600">Rp {{ number_format($booking->bookingAddons->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @else
            <p class="text-gray-500 text-sm mb-4">Belum ada add-on</p>
            @endif

            @if($booking->status == 'pending_payment' || ($booking->payment && $booking->payment->status == 'paid' && $booking->payment->payment_type == 'dp'))
            <form action="{{ route('petugas.bookings.add-addon', $booking) }}" method="POST" id="addonForm">
                @csrf
                <div id="addonRows" class="space-y-3 mb-3">
                    <!-- Dynamic rows will be added here -->
                </div>
                
                <button type="button" onclick="addAddonRow()" class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 text-sm mb-3 border-2 border-dashed border-gray-300">
                    <i class="fas fa-plus mr-2"></i> Tambah Add-on
                </button>
                
                <button type="submit" id="submitAddons" class="w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 text-sm hidden">
                    <i class="fas fa-check mr-2"></i> Simpan Add-ons
                </button>
            </form>

            <script>
                let addonRowCount = 0;
                const availableAddons = @json($availableAddons);

                function addAddonRow() {
                    const container = document.getElementById('addonRows');
                    const rowId = addonRowCount++;
                    
                    const row = document.createElement('div');
                    row.className = 'p-3 bg-gray-50 rounded-lg border border-gray-200';
                    row.id = `addonRow${rowId}`;
                    row.innerHTML = `
                        <div class="flex items-start gap-2">
                            <div class="flex-1 space-y-2">
                                <select name="addons[${rowId}][addon_id]" required class="w-full px-2 py-1.5 border rounded text-sm">
                                    <option value="">-- Pilih --</option>
                                    ${availableAddons.map(addon => 
                                        `<option value="${addon.id}">${addon.name} - Rp ${new Intl.NumberFormat('id-ID').format(addon.price)}</option>`
                                    ).join('')}
                                </select>
                                <input type="number" name="addons[${rowId}][quantity]" value="1" min="1" required 
                                    class="w-full px-2 py-1.5 border rounded text-sm" placeholder="Qty">
                            </div>
                            <button type="button" onclick="removeAddonRow(${rowId})" 
                                class="text-red-600 hover:text-red-800 p-1.5">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                    
                    container.appendChild(row);
                    toggleSubmitButton();
                }

                function removeAddonRow(rowId) {
                    const row = document.getElementById(`addonRow${rowId}`);
                    if (row) {
                        row.remove();
                        toggleSubmitButton();
                    }
                }

                function toggleSubmitButton() {
                    const container = document.getElementById('addonRows');
                    const submitBtn = document.getElementById('submitAddons');
                    submitBtn.classList.toggle('hidden', container.children.length === 0);
                }

                // Add first row by default
                addAddonRow();
            </script>
            @endif
        </div>

        <!-- Bank Info & Payment Upload (Right Sidebar) - hide if fully paid -->
        {{-- Show sidebar only if:
            1. Not fully paid
            2. Status is pending_payment OR (DP paid)
            3. AND (Package NOT paid) - If package is paid, we use the main content form for add-ons
        --}}
        @if(!$isFullyPaid && 
            ($booking->status == 'pending_payment' || ($booking->payment && $booking->payment->status == 'paid' && $booking->payment->payment_type == 'dp')) &&
            !$isPackagePaid
        )
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Transfer Rekening</h3>
            
            <div class="space-y-3 mb-6">
                @foreach($bankAccounts as $bank)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="bg-white p-2 rounded border">
                        <i class="fas fa-university text-gray-600"></i>
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $bank->bank_name }}</p>
                        <div class="flex items-center gap-2">
                            <p class="font-mono text-sm text-gray-600">{{ $bank->account_number }}</p>
                            <button onclick="navigator.clipboard.writeText('{{ $bank->account_number }}')" class="text-xs text-teal-600 hover:text-teal-800">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500">a.n {{ $bank->account_holder }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <h3 class="font-semibold text-gray-800 mb-4">Upload Bukti Pelunasan</h3>
            <form action="{{ route('petugas.bookings.payment', $booking) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="payment_method" value="transfer">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer</label>
                        <input type="file" name="proof_image" accept="image/*" required class="w-full px-3 py-2 border rounded-lg text-sm">
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB</p>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium">
                        <i class="fas fa-upload mr-2"></i> Upload Bukti
                    </button>
                </div>
            </form>
        </div>
        @endif


    </div>
    @endif
</div>

<!-- Image Modal for Bukti Transfer with Zoom -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative w-full h-full flex items-center justify-center" onclick="event.stopPropagation()">
        <!-- Close Button -->
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 text-3xl z-10 bg-black bg-opacity-50 w-12 h-12 rounded-full">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Zoom Controls -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
            <button onclick="zoomOut()" class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg hover:bg-opacity-70">
                <i class="fas fa-search-minus"></i>
            </button>
            <button onclick="resetZoom()" class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg hover:bg-opacity-70">
                <i class="fas fa-compress"></i> Reset
            </button>
            <button onclick="zoomIn()" class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-lg hover:bg-opacity-70">
                <i class="fas fa-search-plus"></i>
            </button>
        </div>
        
        <!-- Image Container -->
        <div class="overflow-auto max-w-full max-h-full" id="imageContainer">
            <img id="modalImage" src="" alt="Bukti Transfer" class="transition-transform duration-200" style="cursor: grab;">
        </div>
    </div>
</div>

<script>
    // Image modal with zoom functionality
    let currentZoom = 1;
    let isDragging = false;
    let startX, startY, scrollLeft, scrollTop;

    function openImageModal(imageUrl) {
        const modal = document.getElementById('imageModal');
        const img = document.getElementById('modalImage');
        img.src = imageUrl;
        modal.classList.remove('hidden');
        resetZoom();
        
        // Add wheel zoom
        const container = document.getElementById('imageContainer');
        container.addEventListener('wheel', handleWheel, { passive: false });
        
        // Add drag to pan
        img.addEventListener('mousedown', startDrag);
        img.addEventListener('mousemove', drag);
        img.addEventListener('mouseup', stopDrag);
        img.addEventListener('mouseleave', stopDrag);
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        currentZoom = 1;
    }

    function zoomIn() {
        currentZoom = Math.min(currentZoom + 0.25, 5);
        applyZoom();
    }

    function zoomOut() {
        currentZoom = Math.max(currentZoom - 0.25, 0.5);
        applyZoom();
    }

    function resetZoom() {
        currentZoom = 1;
        applyZoom();
    }

    function applyZoom() {
        const img = document.getElementById('modalImage');
        img.style.transform = `scale(${currentZoom})`;
    }

    function handleWheel(e) {
        e.preventDefault();
        if (e.deltaY < 0) {
            zoomIn();
        } else {
            zoomOut();
        }
    }

    function startDrag(e) {
        if (currentZoom > 1) {
            isDragging = true;
            const container = document.getElementById('imageContainer');
            startX = e.pageX - container.offsetLeft;
            startY = e.pageY - container.offsetTop;
            scrollLeft = container.scrollLeft;
            scrollTop = container.scrollTop;
            e.target.style.cursor = 'grabbing';
        }
    }

    function drag(e) {
        if (!isDragging) return;
        e.preventDefault();
        const container = document.getElementById('imageContainer');
        const x = e.pageX - container.offsetLeft;
        const y = e.pageY - container.offsetTop;
        const walkX = (x - startX) * 2;
        const walkY = (y - startY) * 2;
        container.scrollLeft = scrollLeft - walkX;
        container.scrollTop = scrollTop - walkY;
    }

    function stopDrag(e) {
        isDragging = false;
        e.target.style.cursor = 'grab';
    }

    // Timer functionality
    @if($booking->status == 'in_progress' && $booking->started_at)
    const startTime = new Date('{{ $booking->started_at->toIso8601String() }}').getTime();
    
    function updateTimer() {
        const now = new Date().getTime();
        const elapsed = now - startTime;
        
        const hours = Math.floor(elapsed / (1000 * 60 * 60));
        const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);
        
        document.getElementById('timer').textContent = 
            String(hours).padStart(2, '0') + ':' + 
            String(minutes).padStart(2, '0') + ':' + 
            String(seconds).padStart(2, '0');
    }
    
    updateTimer();
    setInterval(updateTimer, 1000);
    @endif

    // Payment method toggle
    const paymentMethod = document.getElementById('paymentMethod');
    const proofUpload = document.getElementById('proofUpload');
    const paymentSubmitBtn = document.getElementById('paymentSubmitBtn');
    
    if (paymentMethod) {
        paymentMethod.addEventListener('change', function() {
            // Show/hide upload field
            if (this.value === 'transfer') {
                proofUpload.classList.remove('hidden');
            } else {
                proofUpload.classList.add('hidden');
            }
            
            // Enable/disable submit button
            if (this.value) {
                paymentSubmitBtn.disabled = false;
            } else {
                paymentSubmitBtn.disabled = true;
            }
        });
    }
    
    // Addon payment method toggle
    const addonPaymentMethod = document.getElementById('addonPaymentMethod');
    const addonProofUpload = document.getElementById('addonProofUpload');
    const addonPaymentSubmitBtn = document.getElementById('addonPaymentSubmitBtn');
    
    if (addonPaymentMethod) {
        addonPaymentMethod.addEventListener('change', function() {
            // Show/hide upload field
            if (this.value === 'transfer') {
                addonProofUpload.classList.remove('hidden');
            } else {
                addonProofUpload.classList.add('hidden');
            }
            
            // Enable/disable submit button
            if (this.value) {
                addonPaymentSubmitBtn.disabled = false;
            } else {
                addonPaymentSubmitBtn.disabled = true;
            }
        });
    }
</script>
@endsection
