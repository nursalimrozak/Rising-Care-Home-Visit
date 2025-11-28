<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load(['membership', 'occupation', 'bookings.service', 'loyaltyTransactions']);
        
        $recentBookings = $user->bookings()->latest()->take(5)->get();
        $upcomingBookings = $user->bookings()->whereIn('status', ['scheduled', 'checked_in', 'in_progress'])->orderBy('scheduled_date')->get();
        
        // Check if user has filled health screening
        $hasHealthRecord = \App\Models\UserHealthResponse::where('user_id', $user->id)->exists();

        return view('customer.dashboard', compact('user', 'recentBookings', 'upcomingBookings', 'hasHealthRecord'));
    }
    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|unique:users,phone,' . $user->id,
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'bpjs_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'religion' => 'nullable|string|max:50',
            'marital_status' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:50',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_address' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('customers', 'public');
            $user->avatar = $path;
        }

        // Update Basic Info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];
        $user->date_of_birth = $validated['date_of_birth'];
        $user->bpjs_number = $validated['bpjs_number'];
        $user->gender = $validated['gender'];
        $user->religion = $validated['religion'];
        $user->marital_status = $validated['marital_status'];
        $user->emergency_contact_name = $validated['emergency_contact_name'];
        $user->emergency_contact_relationship = $validated['emergency_contact_relationship'];
        $user->emergency_contact_phone = $validated['emergency_contact_phone'];
        $user->emergency_contact_address = $validated['emergency_contact_address'];

        // Handle Password Update
        if ($request->filled('current_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah.']);
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
