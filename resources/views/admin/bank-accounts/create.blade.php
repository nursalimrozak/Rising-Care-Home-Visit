@extends('layouts.admin')

@section('title', 'Tambah Rekening Bank - RisingCare')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.bank-accounts.index') }}" class="bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Rekening Bank</h1>
            <p class="text-gray-600">Tambahkan rekening bank untuk pembayaran customer</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-8 max-w-2xl">
    <form action="{{ route('admin.bank-accounts.store') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Nama Bank <span class="text-red-500">*</span></label>
            <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('bank_name') border-red-500 @enderror"
                placeholder="Contoh: BCA, Mandiri, BNI">
            @error('bank_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Nomor Rekening <span class="text-red-500">*</span></label>
            <input type="text" name="account_number" value="{{ old('account_number') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('account_number') border-red-500 @enderror"
                placeholder="Contoh: 1234567890">
            @error('account_number')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Atas Nama <span class="text-red-500">*</span></label>
            <input type="text" name="account_holder" value="{{ old('account_holder') }}" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 @error('account_holder') border-red-500 @enderror"
                placeholder="Contoh: PT RisingCare Indonesia">
            @error('account_holder')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Urutan Tampilan</label>
            <input type="number" name="display_order" value="{{ old('display_order', 0) }}" min="0"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"
                placeholder="0">
            <p class="text-xs text-gray-500 mt-1">Semakin kecil angka, semakin atas urutannya</p>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-4 h-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                <span class="ml-2 text-gray-700">Aktif (tampilkan ke customer)</span>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t">
            <a href="{{ route('admin.bank-accounts.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-300 transition">
                Batal
            </a>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition">
                Simpan Rekening
            </button>
        </div>
    </form>
</div>
@endsection
