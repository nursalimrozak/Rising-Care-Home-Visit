@extends('layouts.admin')

@section('title', 'Rekening Bank - RisingCare')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Rekening Bank</h1>
        <p class="text-gray-600">Kelola rekening bank untuk pembayaran customer</p>
    </div>
    <a href="{{ route('admin.bank-accounts.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition font-medium">
        <i class="fas fa-plus mr-2"></i> Tambah Rekening
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
    <div class="flex">
        <i class="fas fa-check-circle text-green-500 mt-1"></i>
        <p class="ml-3 text-green-700">{{ session('success') }}</p>
    </div>
</div>
@endif

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Urutan</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nama Bank</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nomor Rekening</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Atas Nama</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bankAccounts as $account)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-600">{{ $account->display_order }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-university text-teal-600"></i>
                            </div>
                            <span class="font-medium text-gray-900">{{ $account->bank_name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-gray-800">{{ $account->account_number }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $account->account_holder }}</td>
                    <td class="px-6 py-4">
                        @if($account->is_active)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.bank-accounts.edit', $account) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button type="button" onclick="openGlobalDeleteModal('deleteForm{{ $account->id }}', '{{ $account->bank_name }} - {{ $account->account_number }}', 'Hapus Rekening')" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                            <form id="deleteForm{{ $account->id }}" action="{{ route('admin.bank-accounts.destroy', $account) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        Belum ada rekening bank yang terdaftar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
