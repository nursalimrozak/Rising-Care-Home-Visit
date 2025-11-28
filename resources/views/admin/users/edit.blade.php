@extends('layouts.admin')

@section('title', 'Edit User - RisingCare')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
            <p class="text-gray-600">Perbarui informasi user</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-8 max-w-4xl">
    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Column: Avatar -->
            <div class="md:col-span-1 text-center">
                <div class="mb-4">
                    <div class="relative inline-block">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile" class="w-40 h-40 rounded-full object-cover border-4 border-gray-100 shadow-sm">
                        @else
                            <div class="w-40 h-40 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-5xl border-4 border-gray-50 mx-auto">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                        <label for="avatar" class="absolute bottom-2 right-2 bg-teal-600 text-white p-2 rounded-full cursor-pointer hover:bg-teal-700 shadow-md transition">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                </div>
                <p class="text-sm text-gray-500 mb-2">Allowed: JPG, PNG, GIF</p>
                <p class="text-xs text-gray-400">Max size: 2MB</p>
            </div>

            <!-- Right Column: Form Fields -->
            <div class="md:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Role</label>
                        <select name="role" id="role-select" required onchange="toggleCustomerFields()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            <option value="">Pilih Role</option>
                            <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                            <option value="admin_staff" {{ old('role', $user->role) == 'admin_staff' ? 'selected' : '' }}>Admin Staff</option>
                            <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Alamat Lengkap</label>
                    <textarea name="address" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Password (Opsional)</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
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
                                    <option value="{{ $occupation->id }}" {{ old('occupation_id', $user->occupation_id) == $occupation->id ? 'selected' : '' }}>
                                        {{ $occupation->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Membership</label>
                            <select name="membership_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                <option value="">Pilih Membership</option>
                                @foreach($memberships as $membership)
                                    <option value="{{ $membership->id }}" {{ old('membership_id', $user->membership_id) == $membership->id ? 'selected' : '' }}>
                                        {{ $membership->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                        Update User
                    </button>
                </div>
            </div>
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
    
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Find the img element or the placeholder div
                var container = input.parentElement;
                var img = container.querySelector('img');
                
                if (img) {
                    img.src = e.target.result;
                } else {
                    // Create img if it doesn't exist (replacing placeholder)
                    var placeholder = container.querySelector('div');
                    if (placeholder) {
                        var newImg = document.createElement('img');
                        newImg.src = e.target.result;
                        newImg.className = "w-40 h-40 rounded-full object-cover border-4 border-gray-100 shadow-sm";
                        newImg.alt = "Profile";
                        container.replaceChild(newImg, placeholder);
                    }
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Run on load
    document.addEventListener('DOMContentLoaded', function() {
        toggleCustomerFields();
    });
</script>
@endsection
