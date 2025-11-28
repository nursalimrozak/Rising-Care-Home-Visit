<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Occupation;
use App\Models\Membership;
use Illuminate\Http\Request;

class OccupationController extends Controller
{
    public function index()
    {
        // Fetch all active memberships ordered by level
        $memberships = Membership::where('is_active', true)
            ->orderBy('level')
            ->get();
        
        // Group occupations by membership
        $occupationsByMembership = [];
        foreach ($memberships as $membership) {
            $occupationsByMembership[$membership->id] = Occupation::where('membership_id', $membership->id)
                ->withCount('users')
                ->latest()
                ->get();
        }
        
        return view('admin.occupations.index', compact('memberships', 'occupationsByMembership'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:occupations',
            'description' => 'nullable|string',
            'membership_id' => 'required|exists:memberships,id',
        ]);

        Occupation::create($validated);

        \App\Helpers\LogActivity::record(
            'CREATE',
            "Created occupation: {$validated['name']}"
        );

        return back()->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function update(Request $request, Occupation $occupation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:occupations,name,' . $occupation->id,
            'description' => 'nullable|string',
            'membership_id' => 'required|exists:memberships,id',
        ]);

        $occupation->update($validated);

        \App\Helpers\LogActivity::record(
            'UPDATE',
            "Updated occupation: {$occupation->name}",
            $occupation
        );

        return back()->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroy(Occupation $occupation)
    {
        if ($occupation->users()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus pekerjaan yang masih digunakan oleh user.');
        }

        $name = $occupation->name;
        $occupation->delete();

        \App\Helpers\LogActivity::record(
            'DELETE',
            "Deleted occupation: {$name}"
        );

        return back()->with('success', 'Pekerjaan berhasil dihapus.');
    }
}
