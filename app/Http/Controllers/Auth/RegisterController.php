<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Occupation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $occupations = Occupation::where('is_active', true)->get();
        return view('auth.register', compact('occupations'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'occupation_id' => 'required|exists:occupations,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $occupation = Occupation::find($validated['occupation_id']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'occupation_id' => $occupation->id,
            'membership_id' => $occupation->membership_id,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect('/member/dashboard')->with('success', 'Registrasi berhasil! Selamat datang di RisingCare.');
    }
}
