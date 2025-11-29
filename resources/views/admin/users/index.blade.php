@extends('layouts.admin')

@section('title', 'Manajemen User - RisingCare')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
        <p class="text-gray-600">Kelola data staff dan customer</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
        <i class="fas fa-plus mr-2"></i> Tambah User
    </a>
</div>



<div x-data="{ activeTab: 'staff' }">
    <!-- Tabs -->
    <div class="flex space-x-4 mb-6 border-b border-gray-200">
        <button @click="activeTab = 'staff'" 
            :class="{ 'border-teal-600 text-teal-600': activeTab === 'staff', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'staff' }"
            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-user-shield mr-2"></i> Staff & Admin
        </button>
        <button @click="activeTab = 'customer'" 
            :class="{ 'border-teal-600 text-teal-600': activeTab === 'customer', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'customer' }"
            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
            <i class="fas fa-users mr-2"></i> Customers
        </button>
    </div>

    <!-- Staff Table -->
    <div x-show="activeTab === 'staff'" class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Kontak</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($staffs as $staff)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($staff->avatar)
                                    <img src="{{ asset('storage/' . $staff->avatar) }}" alt="{{ $staff->name }}" class="h-10 w-10 rounded-full object-cover mr-3">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-600 font-bold mr-3">
                                        {{ substr($staff->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $staff->name }}</div>
                                    <div class="text-xs text-gray-500">Joined {{ $staff->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($staff->role == 'superadmin')
                                <span class="px-2 py-1 text-xs font-medium bg-purple-100 text-purple-800 rounded-full">Superadmin</span>
                            @elseif($staff->role == 'admin_staff')
                                <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">Admin Staff</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Petugas</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $staff->email }}</div>
                            <div class="text-sm text-gray-500">
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $staff->phone)) }}" target="_blank" class="hover:text-teal-600 hover:underline flex items-center gap-1">
                                    {{ $staff->phone }} <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium {{ $staff->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} rounded-full">
                                {{ $staff->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.users.show', $staff) }}" class="text-teal-600 hover:text-teal-900" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.users.edit', $staff) }}" class="text-blue-600 hover:text-blue-900" title="Edit"><i class="fas fa-edit"></i></a>
                                @if(auth()->id() !== $staff->id)
                                <button type="button" onclick="openGlobalDeleteModal('deleteForm{{ $staff->id }}', '{{ addslashes($staff->name) }}', 'Hapus Staff')" class="text-red-600 hover:text-red-900" title="Hapus"><i class="fas fa-trash"></i></button>
                                    <form id="deleteForm{{ $staff->id }}" action="{{ route('admin.users.destroy', $staff) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Customer Table -->
    <div x-show="activeTab === 'customer'" class="bg-white rounded-xl shadow-sm overflow-hidden" style="display: none;">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Membership</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Pekerjaan</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Kontak</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Loyalty Points</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($customer->avatar)
                                    <img src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->name }}" class="h-10 w-10 rounded-full object-cover mr-3">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold mr-3">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                                    <div class="text-xs text-gray-500">Joined {{ $customer->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($customer->membership)
                                @php
                                    $badgeColor = match($customer->membership->name) {
                                        'Gold' => 'bg-yellow-100 text-yellow-800',
                                        'Silver' => 'bg-gray-100 text-gray-800',
                                        'Bronze' => 'bg-orange-100 text-orange-800',
                                        default => 'bg-blue-100 text-blue-800'
                                    };
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium {{ $badgeColor }} rounded-full">
                                    {{ $customer->membership->name }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $customer->occupation->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                            <div class="text-sm text-gray-500">
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $customer->phone)) }}" target="_blank" class="hover:text-teal-600 hover:underline flex items-center gap-1">
                                    {{ $customer->phone }} <i class="fas fa-external-link-alt text-xs"></i>
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center text-yellow-600 font-medium">
                                <i class="fas fa-star mr-1"></i> {{ $customer->loyalty_points }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.users.show', $customer) }}" class="text-teal-600 hover:text-teal-900" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.users.edit', $customer) }}" class="text-blue-600 hover:text-blue-900" title="Edit"><i class="fas fa-edit"></i></a>
                                <button type="button" onclick="openGlobalDeleteModal('deleteForm{{ $customer->id }}', '{{ addslashes($customer->name) }}', 'Hapus Customer')" class="text-red-600 hover:text-red-900" title="Hapus"><i class="fas fa-trash"></i></button>
                                    <form id="deleteForm{{ $customer->id }}" action="{{ route('admin.users.destroy', $customer) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $customers->links() }}
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endsection
