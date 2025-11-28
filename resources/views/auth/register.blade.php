@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            @php
                $siteLogo = \App\Models\SiteSetting::where('key', 'site_logo')->value('value');
                $siteName = \App\Models\SiteSetting::where('key', 'site_name')->value('value') ?? 'RisingCare';
            @endphp
            
            @if($siteLogo)
                <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-16 mx-auto mb-4 object-contain">
            @endif
            
            <h2 class="text-3xl font-bold text-gray-800">Registrasi</h2>
            <p class="text-gray-600 mt-2">Bergabung dengan {{ $siteName }}</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('registrasi') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Pekerjaan</label>
                <select name="occupation_id" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Pekerjaan</option>
                    @foreach($occupations as $occupation)
                        <option value="{{ $occupation->id }}" {{ old('occupation_id') == $occupation->id ? 'selected' : '' }}>
                            {{ $occupation->name }} ({{ $occupation->membership->name }})
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">Membership akan otomatis disesuaikan dengan pekerjaan Anda</p>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                Daftar
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Sudah punya akun? 
            <a href="{{ route('masuk') }}" class="text-blue-600 hover:underline font-medium">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection
