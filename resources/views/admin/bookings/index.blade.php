@extends('layouts.admin')

@section('title', 'Manajemen Booking - RisingCare')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Manajemen Booking</h1>
    <p class="text-gray-600">Kelola semua booking layanan kesehatan</p>
</div>

<div x-data="{ activeTab: 'regular' }">
    <!-- Tabs -->
    <div class="flex space-x-4 mb-6 border-b border-gray-200">
        <button @click="activeTab = 'regular'" 
            :class="{ 'border-teal-600 text-teal-600': activeTab === 'regular', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'regular' }"
            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-user mr-2"></i> Reguler
        </button>
        <button @click="activeTab = 'eksekutif'" 
            :class="{ 'border-teal-600 text-teal-600': activeTab === 'eksekutif', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'eksekutif' }"
            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-briefcase mr-2"></i> Eksekutif
        </button>
        <button @click="activeTab = 'vip'" 
            :class="{ 'border-teal-600 text-teal-600': activeTab === 'vip', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'vip' }"
            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-crown mr-2"></i> VIP
        </button>
        <button @click="activeTab = 'premium'" 
            :class="{ 'border-teal-600 text-teal-600': activeTab === 'premium', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'premium' }"
            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-gem mr-2"></i> Premium
        </button>
    </div>

    <!-- Reguler Table -->
    <div x-show="activeTab === 'regular'" class="bg-white rounded-xl shadow-sm overflow-hidden">
        @include('admin.bookings.partials.booking-table', ['bookings' => $regularBookings])
    </div>

    <!-- Eksekutif Table -->
    <div x-show="activeTab === 'eksekutif'" class="bg-white rounded-xl shadow-sm overflow-hidden" style="display: none;">
        @include('admin.bookings.partials.booking-table', ['bookings' => $eksekutifBookings])
    </div>

    <!-- VIP Table -->
    <div x-show="activeTab === 'vip'" class="bg-white rounded-xl shadow-sm overflow-hidden" style="display: none;">
        @include('admin.bookings.partials.booking-table', ['bookings' => $vipBookings])
    </div>

    <!-- Premium Table -->
    <div x-show="activeTab === 'premium'" class="bg-white rounded-xl shadow-sm overflow-hidden" style="display: none;">
        @include('admin.bookings.partials.booking-table', ['bookings' => $premiumBookings])
    </div>
</div>
@endsection
