@extends('layouts.customer')

@section('title', 'Detail Booking - RisingCare')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto" x-data="{ showProofModal: false, proofImage: '' }">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
            <h1 class="text-2xl font-bold text-gray-800">Detail Booking</h1>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition flex items-center font-medium text-sm">
                    <i class="fas fa-print mr-2"></i> Print / PDF
                </button>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="bg-teal-50 hover:bg-teal-100 text-teal-700 px-4 py-2 rounded-lg transition flex items-center font-medium text-sm">
                        <i class="fas fa-share-alt mr-2"></i> Share
                    </button>
                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-10" style="display: none;">
                        <a href="mailto:?subject=Booking RisingCare - {{ $booking->booking_number }}&body=Halo, berikut detail booking saya:%0D%0ANo. Booking: {{ $booking->booking_number }}%0D%0ALayanan: {{ $booking->service->name ?? 'Layanan Tidak Tersedia' }}%0D%0ATanggal: {{ \Carbon\Carbon::parse($booking->scheduled_date)->format('d F Y') }}%0D%0ALink: {{ route('booking.show', $booking) }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-teal-600">
                            <i class="fas fa-envelope w-5"></i> Email
                        </a>
                        <a href="https://wa.me/?text=Booking RisingCare - {{ $booking->booking_number }}%0A{{ route('booking.show', $booking) }}" target="_blank"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-teal-600">
                            <i class="fab fa-whatsapp w-5"></i> WhatsApp
                        </a>
                    </div>
                </div>
                <a href="{{ route('booking.my') }}" class="text-teal-600 hover:text-teal-800 font-medium flex items-center ml-2">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 print:hidden" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 print:hidden" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div id="printable-card" class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Header Status -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <span class="text-sm text-gray-500 block">No. Booking</span>
                    <span class="font-mono font-bold text-gray-800">{{ $booking->booking_number }}</span>
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
                            'paid' => 'bg-teal-100 text-teal-800',
                        ];
                        $statusLabels = [
                            'scheduled' => 'Terjadwal',
                            'checked_in' => 'Check-in',
                            'in_progress' => 'Diproses',
                            'pending_payment' => 'Menunggu Pembayaran',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                            'paid' => 'Lunas',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                    </span>
                </div>
            </div>

            @if($booking->booking_type == 'on_site' && $booking->status == 'scheduled')
            <div class="mx-6 mt-6 flex items-start p-4 bg-blue-50 rounded-lg border border-blue-100">
                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                <div class="text-sm text-blue-700">
                    <strong>Informasi Penting:</strong> Saat tiba di klinik, silakan tunjukkan halaman detail booking ini kepada petugas untuk melakukan check-in. Anda dapat menunjukkan nomor booking: <span class="font-mono font-bold">{{ $booking->booking_number }}</span>
                </div>
            </div>
            @endif

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Layanan Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Layanan</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-gray-500 block">Layanan</label>
                                <p class="font-medium text-gray-900">{{ $booking->service->name ?? 'Layanan Tidak Tersedia' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500 block">Kategori</label>
                                <p class="font-medium text-gray-900">{{ $booking->service ? $booking->service->category->name : '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500 block">Tipe Kunjungan</label>
                                <p class="font-medium text-gray-900">
                                    {{ $booking->booking_type == 'home_visit' ? 'Home Visit' : 'Klinik' }}
                                </p>
                            </div>
                            @if($booking->petugas)
                            <div>
                                <label class="text-sm text-gray-500 block">Petugas</label>
                                <p class="font-medium text-gray-900">{{ $booking->petugas->name }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Jadwal Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal & Lokasi</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-gray-500 block">Tanggal</label>
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('d F Y') }}</p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500 block">Jam</label>
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->scheduled_time)->format('H:i') }}</p>
                            </div>
                            @if($booking->status == 'completed' && !is_null($booking->duration_minutes))
                            <div>
                                <label class="text-sm text-gray-500 block">Durasi Treatment</label>
                                <p class="font-medium text-gray-900">{{ floor($booking->duration_minutes / 60) > 0 ? floor($booking->duration_minutes / 60) . ' jam ' : '' }}{{ $booking->duration_minutes % 60 }} menit</p>
                            </div>
                            @endif
                            @if($booking->booking_type == 'home_visit')
                            <div>
                                <label class="text-sm text-gray-500 block">Alamat Kunjungan</label>
                                <p class="font-medium text-gray-900">{{ $booking->customer_address }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($booking->packagePurchase)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Paket</h3>
                    <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-box text-teal-600"></i>
                                    <span class="font-bold text-teal-900">{{ $booking->packagePurchase->package->name }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Kunjungan ke:</span>
                                        <span class="font-semibold text-gray-900">{{ $booking->visit_number }} dari {{ $booking->packagePurchase->total_visits }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Sisa kunjungan:</span>
                                        <span class="font-semibold text-teal-600">{{ $booking->packagePurchase->getRemainingVisits() }}x</span>
                                    </div>
                                </div>
                                @if($booking->packagePurchase->expires_at)
                </div>
                @endif

                @if($booking->customer_notes)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Catatan Tambahan</h3>
                    <p class="text-gray-600 bg-gray-50 p-4 rounded-lg">{{ $booking->customer_notes }}</p>
                </div>
                @endif

                @if($booking->payment)
                @php
                    // Check if this is a package booking
                    $isPackageBooking = $booking->packagePurchase !== null;
                    
                    // Calculate totals dynamically
                    $servicePrice = $booking->estimated_price;
                    $addonsTotal = $booking->bookingAddons->sum('subtotal');
                    
                    if ($isPackageBooking) {
                        // Check if package is pending (new purchase)
                        if ($booking->packagePurchase->status == 'pending') {
                            // New purchase: treat like regular booking payment
                            $totalAmount = $booking->payment->total_amount;
                            $paidAmount = 0;
                            
                            if ($booking->payment->status == 'paid') {
                                $paidAmount = $totalAmount;
                            } elseif ($booking->payment->payment_type == 'dp' && $booking->payment->paid_at) {
                                $paidAmount = $booking->payment->required_amount;
                            }
                        } else {
                            // Existing package: package is already paid, only add-ons need payment
                            $totalAmount = $addonsTotal;
                            $paidAmount = 0; 
                            
                            // Check if add-ons have been paid
                            if ($booking->payment->status == 'paid' && $addonsTotal > 0) {
                                $paidAmount = $addonsTotal;
                            }
                        }
                    } else {
                        // For regular bookings: normal DP/Full payment logic
                        $totalAmount = $servicePrice + $addonsTotal;
                        
                        // Apply discounts
                        if($booking->payment->membership_discount > 0) $totalAmount -= $booking->payment->membership_discount;
                        if($booking->payment->voucher_discount > 0) $totalAmount -= $booking->payment->voucher_discount;
                        
                        $paidAmount = 0;
                        if ($booking->payment->payment_type == 'dp') {
                            // Check if DP has been paid using paid_at
                            if ($booking->payment->paid_at) {
                                $paidAmount = $booking->payment->required_amount;
                            }
                        } elseif ($booking->payment->status == 'paid') {
                             // If fully paid, assume total amount
                             $paidAmount = $totalAmount;
                        }
                    }


                    $remainingAmount = $totalAmount - $paidAmount;
                    
                    // Determine effective payment status (handle data inconsistencies)
                    $paymentStatus = $booking->payment->status;
                    
                    // If status is paid but no proof and no paid_at, treat as pending (unless it's a legacy record or admin override)
                    // This fixes the issue where new bookings might incorrectly show as paid
                    if ($paymentStatus == 'paid' && !$booking->payment->paid_at && !$booking->payment->payment_proof) {
                        $paymentStatus = 'pending';
                        $paidAmount = 0; // Reset paid amount if treating as pending
                        $remainingAmount = $totalAmount; // Reset remaining amount
                    }

                    // Check if fully paid (for DP scenario with final payment)
                    $isFullyPaid = ($booking->status == 'completed' || ($paymentStatus == 'paid' && $remainingAmount <= 0));
                @endphp

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
                    
                    <!-- Payment Status -->
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100 mb-4">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">Transfer Bank</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($isPackageBooking && $booking->packagePurchase->status != 'pending')
                                            Tipe: Pembayaran Add-ons
                                        @else
                                            Tipe: {{ $booking->payment->payment_type == 'dp' ? 'DP (Down Payment)' : 'Full Payment' }}
                                        @endif
                                    </p>
                                    @if($remainingAmount > 0)
                                    <p class="text-xs text-gray-500">
                                        @if($isPackageBooking && $booking->packagePurchase->status != 'pending')
                                            Total Add-ons: Rp {{ number_format($remainingAmount, 0, ',', '.') }}
                                        @else
                                            Sisa: Rp {{ number_format($remainingAmount, 0, ',', '.') }}
                                        @endif
                                    </p>
                                    @endif
                                </div>
                            </div>
                            <div>
                                @if($paymentStatus == 'paid')
                                    @if($booking->payment->payment_type == 'dp' && $remainingAmount > 0)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-bold">DP TERBAYAR</span>
                                    @else
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">LUNAS</span>
                                    @endif
                                @elseif($paymentStatus == 'pending_verification')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">VERIFIKASI</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold">PENDING</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Bank Account Info (if payment pending or pelunasan needed, AND booking not completed) -->
                    @if($booking->status != 'completed' && (($paymentStatus == 'pending') || ($booking->payment->payment_type == 'dp' && $paymentStatus == 'paid' && $remainingAmount > 0)))
                    @if($bankAccounts->count() > 0)
                    <div class="bg-teal-50 border border-teal-200 rounded-lg p-4 mb-4">
                        <h4 class="font-semibold text-teal-900 mb-3 flex items-center">
                            <i class="fas fa-university mr-2"></i> Rekening Tujuan Transfer
                        </h4>
                        <div class="space-y-3">
                            @foreach($bankAccounts as $account)
                            <div class="bg-white rounded-lg p-3 border border-teal-100">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $account->bank_name }}</p>
                                        <p class="text-sm text-gray-600 font-mono">{{ $account->account_number }}</p>
                                        <p class="text-xs text-gray-500">a.n. {{ $account->account_holder }}</p>
                                    </div>
                                    <button onclick="copyToClipboard('{{ $account->account_number }}')" 
                                        class="px-3 py-1.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-xs font-medium">
                                        <i class="fas fa-copy mr-1"></i> Salin
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-teal-700 mt-3">
                            <i class="fas fa-info-circle mr-1"></i> Transfer sejumlah 
                            <strong>
                                @if($paymentStatus == 'pending')
                                    Rp {{ number_format($booking->payment->required_amount, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($remainingAmount, 0, ',', '.') }} (Pelunasan)
                                @endif
                            </strong>
                        </p>
                    </div>
                    @endif
                    @endif

                    <!-- Upload Payment Proof (hide if completed) -->
                    @if(!$isFullyPaid)
                        @if($paymentStatus == 'pending')
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Upload Bukti Transfer {{ ($booking->payment->payment_type == 'dp' && $booking->payment->paid_at == null) ? 'DP' : 'Pelunasan' }}</h4>
                            <form action="{{ route('booking.upload-payment', $booking->booking_number) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <input type="file" name="payment_proof" accept="image/*" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                                </div>
                                <button type="submit" class="w-full bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition font-medium">
                                    <i class="fas fa-upload mr-2"></i> Upload Bukti Pembayaran
                                </button>
                            </form>
                        </div>
                        @elseif($booking->payment->payment_type == 'dp' && $paymentStatus == 'paid' && $remainingAmount > 0)
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Upload Bukti Transfer Pelunasan</h4>
                            <form action="{{ route('booking.upload-payment', $booking->booking_number) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <input type="file" name="payment_proof" accept="image/*" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                                </div>
                                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                                    <i class="fas fa-upload mr-2"></i> Upload Bukti Pelunasan
                                </button>
                            </form>
                        </div>
                        @elseif($booking->payment->status == 'pending_verification')
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-clock text-yellow-600 mt-1"></i>
                                <div>
                                    <p class="font-medium text-yellow-900">Menunggu Verifikasi Admin</p>
                                    <p class="text-sm text-yellow-700 mt-1">Bukti pembayaran Anda sedang diverifikasi oleh admin.</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif

                    <!-- Display Proofs -->
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @if($booking->payment->payment_proof)
                        <button @click="showProofModal = true; proofImage = '{{ asset('storage/' . $booking->payment->payment_proof) }}'" 
                            class="w-full bg-teal-50 text-teal-700 border border-teal-200 px-4 py-2 rounded-lg hover:bg-teal-100 transition font-medium flex items-center justify-center text-sm">
                            <i class="fas fa-image mr-2"></i> Lihat Bukti Transfer DP
                        </button>
                        @endif

                        @if($booking->payment->final_payment_proof)
                        <button @click="showProofModal = true; proofImage = '{{ asset('storage/' . $booking->payment->final_payment_proof) }}'" 
                            class="w-full bg-blue-50 text-blue-700 border border-blue-200 px-4 py-2 rounded-lg hover:bg-blue-100 transition font-medium flex items-center justify-center text-sm">
                            <i class="fas fa-image mr-2"></i> Lihat Bukti Pelunasan
                        </button>
                        @endif
                    </div>

                </div>
                @endif

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

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h3>
                    <div class="bg-white rounded-lg p-4 border border-gray-100 space-y-3">
                        <!-- Service/Package Price -->
                        <div class="flex justify-between text-gray-800">
                            <span>
                                @if($booking->packagePurchase)
                                    Paket {{ $booking->packagePurchase->package->name }}
                                @else
                                    {{ $booking->service->name ?? 'Layanan Tidak Tersedia' }}
                                @endif
                            </span>
                            @if($isPackageBooking)
                                @if($booking->packagePurchase->status == 'pending')
                                    <!-- Pending Package: Show Price -->
                                    <span class="font-medium">Rp {{ number_format($booking->payment->total_amount, 0, ',', '.') }}</span>
                                @else
                                    <!-- Active Package: Show Paid Badge -->
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-green-600">Sudah Dibayar (Paket)</span>
                                        <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded-full text-xs font-bold">LUNAS</span>
                                    </div>
                                @endif
                            @else
                                <span class="font-medium">Rp {{ number_format($booking->estimated_price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        
                        @if($isPackageBooking && $booking->packagePurchase->status != 'pending')
                        <!-- Info note for package bookings (only if active) -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-3">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                                <p class="text-xs text-blue-800">
                                    <strong>Catatan:</strong> Pembayaran di atas belum termasuk add-ons yang digunakan saat treatment berlangsung. Pembayaran add-ons akan ditagihkan setelah treatment selesai.
                                </p>
                            </div>
                        </div>
                        @endif

                        <!-- Add-ons -->
                        @if($booking->bookingAddons && $booking->bookingAddons->count() > 0)
                            <div class="flex justify-between text-gray-800">
                                <span>Add-ons</span>
                                <span class="font-medium">Rp {{ number_format($booking->bookingAddons->sum('subtotal'), 0, ',', '.') }}</span>
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

                        @if($booking->payment && !$isPackageBooking)
                            <!-- Discounts (only for regular bookings) -->
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
                        @endif

                        @if($booking->payment)
                            <!-- Total -->
                            @if(!$isPackageBooking || ($isPackageBooking && ($totalAmount > 0 || $booking->packagePurchase->status == 'pending')))
                            <div class="border-t border-gray-200 pt-3 flex justify-between font-bold text-gray-900 text-lg">
                                <span>{{ ($isPackageBooking && $booking->packagePurchase->status != 'pending') ? 'Total Add-ons' : 'Total Pembayaran' }}</span>
                                <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                            </div>
                            @endif

                            <!-- Payment Details -->
                            @if($paidAmount > 0)
                            <div class="flex justify-between text-green-600 font-medium pt-1">
                                <span>Sudah Dibayar ({{ $booking->payment->payment_type == 'dp' ? 'DP' : 'Full' }})</span>
                                <span>Rp {{ number_format($paidAmount, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            
                            @if($remainingAmount > 0)
                            <div class="border-t border-gray-200 pt-3 flex justify-between font-bold text-orange-600 text-lg">
                                <span>Sisa Pembayaran</span>
                                <span>Rp {{ number_format($remainingAmount, 0, ',', '.') }}</span>
                            </div>
                            @if($booking->payment->payment_type == 'dp' && $booking->payment->final_payment_proof && $booking->payment->status == 'paid')
                            {{-- Final payment verified, show LUNAS badge --}}
                            <div class="mt-2 text-right">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">LUNAS</span>
                            </div>
                            @else
                            <p class="text-xs text-gray-500 italic mt-1">* Sisa pembayaran dibayar setelah treatment selesai</p>
                            @endif
                            @elseif(!$isPackageBooking)
                            {{-- Only show "Sisa Pembayaran: Rp 0" for regular bookings, not package bookings --}}
                            <div class="border-t border-gray-200 pt-3 flex justify-between font-bold text-gray-900 text-lg">
                                <span>Sisa Pembayaran</span>
                                <span>Rp 0</span>
                            </div>
                            <div class="mt-2 text-right">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">LUNAS</span>
                            </div>
                            @endif
                        @else
                            <!-- Estimated Total (No Payment Record yet) -->
                            <div class="border-t border-gray-200 pt-3 flex justify-between font-bold text-gray-900 text-lg">
                                <span>Estimasi Total</span>
                                <span>Rp {{ number_format($booking->estimated_price, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show toast or alert
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 text-sm flex items-center';
            toast.innerHTML = '<i class="fas fa-check-circle text-green-400 mr-2"></i> Nomor rekening disalin!';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>

                <!-- Review Section -->
                @if($booking->status == 'completed')
                    @if($booking->review)
                        <!-- Display existing review -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Ulasan Anda</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $booking->review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="ml-2 text-sm text-gray-600">{{ $booking->review->rating }}/5</span>
                                </div>
                                @if($booking->review->comment)
                                    <p class="text-gray-700">{{ $booking->review->comment }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">Diberikan pada {{ $booking->review->created_at->format('d M Y H:i') }}</p>
                            </div>
                            
                            <!-- Thank You Message -->
                            <div class="mt-6 text-center bg-teal-50 rounded-xl p-6 border border-teal-100">
                                <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-heart text-teal-600 text-2xl"></i>
                                </div>
                                <h4 class="text-xl font-bold text-teal-800 mb-2">Terima Kasih!</h4>
                                <p class="text-teal-700">
                                    Terima kasih telah mempercayakan kesehatan Anda kepada RisingCare. Kami sangat menghargai ulasan Anda dan berharap dapat melayani Anda kembali dengan lebih baik di masa depan. Semoga Anda sehat selalu!
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Review form -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Berikan Ulasan</h3>
                            <form action="{{ route('booking.review.store', $booking) }}" method="POST" class="space-y-4 print:hidden">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating Layanan</label>
                                    <div x-data="{ rating: 0, hoverRating: 0 }" class="flex items-center space-x-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" required class="hidden" @click="rating = {{ $i }}">
                                                <i @mouseenter="hoverRating = {{ $i }}" 
                                                   @mouseleave="hoverRating = 0"
                                                   @click="rating = {{ $i }}"
                                                   :class="(hoverRating >= {{ $i }} || (hoverRating === 0 && rating >= {{ $i }})) ? 'text-yellow-400' : 'text-gray-300'"
                                                   class="fas fa-star text-2xl transition cursor-pointer"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Komentar (Opsional)</label>
                                    <textarea name="comment" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Bagikan pengalaman Anda..."></textarea>
                                </div>
                                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition font-medium">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Ulasan
                                </button>
                            </form>
                            
                            <!-- Thank You Message (Pre-review) -->
                            <div class="mt-8 text-center">
                                <p class="text-gray-600 italic">
                                    "Terima kasih telah memilih RisingCare. Kepuasan dan kesehatan Anda adalah prioritas utama kami."
                                </p>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end print:hidden">
                    @if(in_array($booking->status, ['scheduled', 'pending_payment']) && !$booking->petugas_id)
                    <button onclick="openCancelModal()" class="bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition flex items-center font-medium">
                        <i class="fas fa-times-circle mr-2"></i> Batalkan Reservasi
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Modal -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center">
    <div class="relative p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">Batalkan Reservasi?</h3>
            <div class="mt-2 px-2 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin membatalkan reservasi ini? Slot waktu Anda akan dilepas dan dapat dipesan oleh orang lain. Tidak ada biaya pembatalan yang dikenakan.
                </p>
            </div>
            <div class="flex justify-center gap-3 mt-4">
                <button onclick="closeCancelModal()" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-lg w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Tidak, Kembali
                </button>
                <form action="{{ route('booking.cancel', $booking) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-lg w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Ya, Batalkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openCancelModal() {
        document.getElementById('cancelModal').classList.remove('hidden');
        document.getElementById('cancelModal').classList.add('flex');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
        document.getElementById('cancelModal').classList.remove('flex');
    }
</script>
@endsection

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printable-card, #printable-card * {
            visibility: visible;
        }
        #printable-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            box-shadow: none !important;
            border: none !important;
        }
        .print\:hidden {
            display: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endpush
