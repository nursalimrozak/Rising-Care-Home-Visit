<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::withCount('users')->latest()->paginate(15);
        return view('admin.memberships.index', compact('memberships'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:memberships',
            'description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        Membership::create($validated);

        \App\Helpers\LogActivity::record(
            'CREATE',
            "Created membership: {$validated['name']} with {$validated['discount_percentage']}% discount"
        );

        return back()->with('success', 'Membership berhasil ditambahkan.');
    }

    public function update(Request $request, Membership $membership)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:memberships,name,' . $membership->id,
            'description' => 'nullable|string',
            'discount_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $membership->update($validated);

        \App\Helpers\LogActivity::record(
            'UPDATE',
            "Updated membership: {$membership->name}",
            $membership
        );

        return back()->with('success', 'Membership berhasil diperbarui.');
    }

    public function destroy(Membership $membership)
    {
        if ($membership->users()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus membership yang masih digunakan oleh user.');
        }

        $name = $membership->name;
        $membership->delete();

        \App\Helpers\LogActivity::record(
            'DELETE',
            "Deleted membership: {$name}"
        );

        return back()->with('success', 'Membership berhasil dihapus.');
    }
}
