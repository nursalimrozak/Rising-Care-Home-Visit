<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Membership;
use App\Models\ServicePrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('category')->get();
        $categories = ServiceCategory::all();
        $memberships = Membership::all();
        return view('admin.services.index', compact('services', 'categories', 'memberships'));
    }

    public function create()
    {
        $categories = ServiceCategory::all();
        $memberships = Membership::all();
        return view('admin.services.create', compact('categories', 'memberships'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:service_categories,id',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'service_type' => 'required|in:on_site,home_visit,both',
            'prices' => 'array',
            'prices.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $service = Service::create([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'],
                'duration_minutes' => $validated['duration_minutes'],
                'service_type' => $validated['service_type'],
                'is_active' => true,
            ]);

            // Create specific prices for memberships
            if (isset($validated['prices'])) {
                foreach ($validated['prices'] as $membershipId => $price) {
                    if ($price !== null) {
                        ServicePrice::create([
                            'service_id' => $service->id,
                            'membership_id' => $membershipId,
                            'price' => $price,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::all();
        $memberships = Membership::all();
        $service->load('prices');
        
        // Map existing prices for easier access in view
        $currentPrices = [];
        foreach ($service->prices as $price) {
            $currentPrices[$price->membership_id] = $price->price;
        }

        // Return JSON for AJAX requests (modal)
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'service' => $service,
                'categories' => $categories,
                'memberships' => $memberships,
                'currentPrices' => $currentPrices
            ]);
        }

        return view('admin.services.edit', compact('service', 'categories', 'memberships', 'currentPrices'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:service_categories,id',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'service_type' => 'required|in:on_site,home_visit,both',
            'is_active' => 'boolean',
            'prices' => 'array',
            'prices.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $service->update([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'],
                'duration_minutes' => $validated['duration_minutes'],
                'service_type' => $validated['service_type'],
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            // Update specific prices
            // First delete old prices to handle removals/updates cleanly
            ServicePrice::where('service_id', $service->id)->delete();

            if (isset($validated['prices'])) {
                foreach ($validated['prices'] as $membershipId => $price) {
                    if ($price !== null && $price !== '') {
                        ServicePrice::create([
                            'service_id' => $service->id,
                            'membership_id' => $membershipId,
                            'price' => $price,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Service $service)
    {
        $service->delete(); // Soft delete
        return back()->with('success', 'Layanan berhasil dihapus');
    }
}
