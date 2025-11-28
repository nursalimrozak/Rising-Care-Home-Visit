<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceAddon;
use Illuminate\Http\Request;

class ServiceAddonController extends Controller
{
    public function index()
    {
        $addons = ServiceAddon::latest()->paginate(15);
        return view('admin.service-addons.index', compact('addons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['is_active'] = true;

        ServiceAddon::create($validated);

        \App\Helpers\LogActivity::record(
            'CREATE',
            "Created service addon: {$validated['name']}"
        );

        return back()->with('success', 'Add-on berhasil ditambahkan.');
    }

    public function update(Request $request, ServiceAddon $serviceAddon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $serviceAddon->update($validated);

        \App\Helpers\LogActivity::record(
            'UPDATE',
            "Updated service addon: {$serviceAddon->name}",
            $serviceAddon
        );

        return back()->with('success', 'Add-on berhasil diperbarui.');
    }

    public function destroy(ServiceAddon $serviceAddon)
    {
        $name = $serviceAddon->name;
        $serviceAddon->delete();

        \App\Helpers\LogActivity::record(
            'DELETE',
            "Deleted service addon: {$name}"
        );

        return back()->with('success', 'Add-on berhasil dihapus.');
    }
}
