@extends(Auth::check() && Auth::user()->role == 'customer' ? 'layouts.customer' : 'layouts.app')

@section('title', 'Booking Berhasil - RisingCare')

@section('content')
<div class="{{ Auth::check() && Auth::user()->role == 'customer' ? 'max-w-7xl mx-auto py-8' : 'min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4' }}">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-xl overflow-hidden text-center p-8 {{ Auth::check() && Auth::user()->role == 'customer' ? 'mx-auto' : '' }}">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check text-green-600 text-4xl"></i>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Booking Berhasil!</h1>
        <p class="text-gray-600 mb-8">Terima kasih telah mempercayakan kesehatan Anda kepada RisingCare.</p>
        
        <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left border border-gray-100">
            <div class="flex justify-between mb-3 border-b pb-2">
                <span class="text-gray-500">Nomor Booking</span>
                <span class="font-bold text-gray-800 font-mono">{{ $booking->booking_number }}</span>
            </div>
            
            @if($booking->packagePurchase)
            <div class="flex justify-between mb-3">
                <span class="text-gray-500">Paket</span>
                <span class="font-medium text-gray-800">{{ $booking->packagePurchase->package->name }}</span>
            </div>
            <div class="flex justify-between mb-3">
                <span class="text-gray-500">Maksimal Penggunaan</span>
                <span class="font-medium text-gray-800">{{ $booking->packagePurchase->total_visits }}x kunjungan</span>
            </div>
            @if($booking->packagePurchase->expires_at)
            <div class="flex justify-between mb-3">
                <span class="text-gray-500">Durasi Penggunaan</span>
                <span class="font-medium text-gray-800">{{ $booking->packagePurchase->package->validity_weeks }} minggu</span>
            </div>
            <div class="flex justify-between mb-3">
                <span class="text-gray-500">Kunjungan Pertama</span>
                <span class="font-medium text-gray-800">
                    {{ $booking->scheduled_date ? \Carbon\Carbon::parse($booking->scheduled_date)->format('d M Y') : '-' }}
                </span>
            </div>
            <div class="flex justify-between mb-3">
                <span class="text-gray-500">Berlaku Hingga</span>
                <span class="font-medium text-gray-800">
                    {{ $booking->packagePurchase->expires_at ? $booking->packagePurchase->expires_at->format('d M Y') : '-' }}
                </span>
            </div>
            @endif
            @else
            <div class="flex justify-between mb-3">
                <span class="text-gray-500">Paket</span>
                <span class="font-medium text-gray-800">Reguler (1x kunjungan)</span>
            </div>
            @endif
            
            <div class="flex justify-between mb-3">
                <span class="text-gray-500">Tanggal</span>
                <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->scheduled_date)->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between mb-3">
                <span class="text-gray-500">Jam</span>
                <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->scheduled_time)->format('H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Tipe</span>
                <span class="font-medium text-gray-800">
                    {{ $booking->booking_type == 'home_visit' ? 'Home Visit' : 'Klinik' }}
                </span>
            </div>
        </div>
        
        @if($booking->packagePurchase && $booking->packagePurchase->package->validity_weeks > 0)
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-8 text-left">
            <div class="flex">
                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Informasi Penting:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Masa berlaku paket <strong>{{ $booking->packagePurchase->package->validity_weeks }} minggu</strong> dimulai sejak sesi terapi pertama Anda (saat petugas memulai layanan)</li>
                        <li>Paket akan berakhir secara otomatis apabila masa berlaku telah habis atau seluruh kunjungan telah digunakan</li>
                        <li>Pastikan Anda memanfaatkan seluruh kunjungan sebelum masa berlaku paket berakhir</li>
                    </ul>
                </div>
            </div>
        </div>
        @endif
        
        <div class="space-y-3">
            <a href="{{ route('booking.my') }}" class="block w-full bg-teal-600 text-white py-3 rounded-lg font-bold hover:bg-teal-700 transition">
                Lihat Booking Saya
            </a>
            <a href="{{ route('beranda') }}" class="block w-full bg-white text-gray-600 py-3 rounded-lg font-bold hover:bg-gray-50 border border-gray-200 transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
