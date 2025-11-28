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
            
            <h2 class="text-3xl font-bold text-gray-800">Masuk</h2>
            <p class="text-gray-600 mt-2">Selamat datang kembali di {{ $siteName }}</p>
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

        <!-- Demo Credentials -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="font-semibold text-blue-800 mb-2">🔑 Demo Credentials:</p>
            <div class="space-y-2 text-sm text-blue-700">
                <div class="bg-white rounded p-2">
                    <p class="font-medium">Superadmin:</p>
                    <p>Email: <code class="bg-blue-100 px-2 py-1 rounded">superadmin@risingcare.com</code></p>
                    <p>Password: <code class="bg-blue-100 px-2 py-1 rounded">password</code></p>
                </div>
                <div class="bg-white rounded p-2">
                    <p class="font-medium">Admin Staff:</p>
                    <p>Email: <code class="bg-blue-100 px-2 py-1 rounded">admin@risingcare.com</code></p>
                    <p>Password: <code class="bg-blue-100 px-2 py-1 rounded">password</code></p>
                </div>
                <div class="bg-white rounded p-2">
                    <p class="font-medium">Petugas:</p>
                    <p>Email: <code class="bg-blue-100 px-2 py-1 rounded">petugas@risingcare.com</code></p>
                    <p>Password: <code class="bg-blue-100 px-2 py-1 rounded">password</code></p>
                </div>
                <div class="bg-white rounded p-2">
                    <p class="font-medium">Customer:</p>
                    <p>Email: <code class="bg-blue-100 px-2 py-1 rounded">customer@risingcare.com</code></p>
                    <p>Password: <code class="bg-blue-100 px-2 py-1 rounded">password</code></p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('masuk') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600">
                    <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                Masuk
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6">
            Belum punya akun? 
            <a href="{{ route('registrasi') }}" class="text-blue-600 hover:underline font-medium">Daftar sekarang</a>
        </p>
    </div>
</div>
@endsection
