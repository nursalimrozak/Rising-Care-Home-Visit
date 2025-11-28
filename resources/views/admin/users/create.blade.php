@extends('layouts.admin')

@section('title', 'Tambah User - RisingCare')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah User Baru</h1>
            <p class="text-gray-600">Isi form berikut untuk menambahkan user baru</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-8 max-w-3xl">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Role</label>
                <select name="role" id="role-select" required onchange="toggleCustomerFields()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Pilih Role</option>
                    <option value="superadmin">Superadmin</option>
                    <option value="admin_staff">Admin Staff</option>
                    <option value="petugas">Petugas</option>
                    <option value="customer">Customer</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-medium mb-2">Password</label>
            <input type="password" name="password" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
        </div>

        <!-- Customer Specific Fields -->
        <div id="customer-fields" class="hidden border-t pt-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Data Customer</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Pekerjaan</label>
                    <select name="occupation_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">Pilih Pekerjaan</option>
                        @foreach($occupations as $occupation)
                            <option value="{{ $occupation->id }}">{{ $occupation->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Membership (Opsional)</label>
                    <select name="membership_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">Pilih Membership</option>
                        @foreach($memberships as $membership)
                            <option value="{{ $membership->id }}">{{ $membership->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Jika kosong, akan otomatis mengikuti pekerjaan</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-8">
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-teal-700 transition">
                Simpan User
            </button>
        </div>
    </form>
</div>

<script>
    function toggleCustomerFields() {
        const role = document.getElementById('role-select').value;
        const customerFields = document.getElementById('customer-fields');
        if (role === 'customer') {
            customerFields.classList.remove('hidden');
        } else {
            customerFields.classList.add('hidden');
        }
    }
</script>
@endsection
