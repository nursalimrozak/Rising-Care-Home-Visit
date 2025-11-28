<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use App\Models\ServicePackage;
use App\Models\PackagePrice;
use App\Models\Membership;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackagePriceController extends Controller
{
    public function index()
    {
        $services = Service::with(['packagePrices.package', 'packagePrices.membership'])
            ->where('is_active', true)
            ->get();
        
        return view('admin.package-prices.index', compact('services'));
    }

    public function edit(Service $service)
    {
        $packages = ServicePackage::where('is_active', true)->get();
        $memberships = Membership::where('is_active', true)->orderBy('level')->get();
        
        // Get existing prices
        $existingPrices = PackagePrice::where('service_id', $service->id)
            ->get()
            ->groupBy(function($item) {
                return $item->package_id . '_' . $item->membership_id;
            });
        
        return view('admin.package-prices.edit', compact('service', 'packages', 'memberships', 'existingPrices'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'prices' => 'required|array',
            'prices.*.*' => 'required|numeric|min:0',
        ]);

        foreach ($validated['prices'] as $packageId => $memberships) {
            foreach ($memberships as $membershipId => $price) {
                PackagePrice::updateOrCreate(
                    [
                        'service_id' => $service->id,
                        'package_id' => $packageId,
                        'membership_id' => $membershipId,
                    ],
                    [
                        'price' => $price,
                    ]
                );
            }
        }

        return redirect()->route('admin.package-prices.index')
            ->with('success', 'Harga paket berhasil diperbarui');
    }
}
