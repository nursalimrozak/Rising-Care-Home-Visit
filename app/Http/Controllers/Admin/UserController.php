<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Occupation;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $staffs = User::whereIn('role', ['superadmin', 'admin_staff', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->get();

        $customers = User::where('role', 'customer')
            ->with(['occupation', 'membership'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('staffs', 'customers'));
    }

    public function create()
    {
        $occupations = Occupation::all();
        $memberships = Membership::all();
        return view('admin.users.create', compact('occupations', 'memberships'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:superadmin,admin_staff,petugas,customer',
            'occupation_id' => 'nullable|exists:occupations,id',
            'membership_id' => 'nullable|exists:memberships,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Auto assign membership based on occupation if not provided
        if ($validated['role'] == 'customer' && !empty($validated['occupation_id']) && empty($validated['membership_id'])) {
            $occupation = Occupation::find($validated['occupation_id']);
            $validated['membership_id'] = $occupation->membership_id;
        }

        $user = User::create($validated);
        
        \App\Helpers\LogActivity::record('CREATE', "Created user {$user->name} ({$user->role})", $user);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        $user->load(['occupation', 'membership', 'bookings.service', 'loyaltyTransactions', 'bookingsAsPetugas.customer', 'bookingsAsPetugas.service']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $occupations = Occupation::all();
        $memberships = Membership::all();
        return view('admin.users.edit', compact('user', 'occupations', 'memberships'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|unique:users,phone,' . $user->id,
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|in:superadmin,admin_staff,petugas,customer',
            'occupation_id' => 'nullable|exists:occupations,id',
            'membership_id' => 'nullable|exists:memberships,id',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Determine folder based on role
            $folder = 'customers';
            if (in_array($user->role, ['superadmin', 'admin_staff', 'petugas'])) {
                $folder = 'admin';
            }
            
            // Store file in public disk
            $path = $file->store($folder, 'public');
            $validated['avatar'] = $path;
            
            // Delete old avatar if exists
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
        }

        $user->update($validated);
        
        \App\Helpers\LogActivity::record('UPDATE', "Updated user {$user->name}", $user);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }
        
        $name = $user->name;
        $user->delete();
        
        \App\Helpers\LogActivity::record('DELETE', "Deleted user {$name}", null);
        
        return back()->with('success', 'User berhasil dihapus');
    }
}
