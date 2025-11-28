<?php

namespace App\Http\Controllers\Customer;

use App\Models\PackagePurchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function index()
    {
        // Self-healing: Check for active packages with cancelled initial booking
        $activePackages = PackagePurchase::where('customer_id', Auth::id())
            ->where('status', 'active')
            ->with('bookings')
            ->get();

        foreach ($activePackages as $pp) {
            $initialBooking = $pp->bookings->where('visit_number', 1)->first();
            if ($initialBooking && $initialBooking->status == 'cancelled') {
                // Fix inconsistent state
                try {
                    $pp->update(['status' => 'cancelled']);
                } catch (\Exception $e) {
                    // Fallback if 'cancelled' enum not yet migrated
                    $pp->update(['status' => 'expired']);
                }
            }
        }

        $packages = PackagePurchase::where('customer_id', Auth::id())
            ->with(['service', 'package', 'bookings'])
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('customer.packages.index', compact('packages'));
    }

    public function show($id)
    {
        // Decode base64 encoded ID
        $decodedId = base64_decode($id);
        
        // Validate decoded ID is numeric
        if (!is_numeric($decodedId)) {
            abort(404);
        }
        
        $packagePurchase = PackagePurchase::findOrFail($decodedId);
        
        // Ensure user owns the package
        if ($packagePurchase->customer_id !== Auth::id()) {
            abort(403);
        }

        $packagePurchase->load(['service', 'package', 'bookings.petugas']);
        
        return view('customer.packages.show', compact('packagePurchase'));
    }
}
