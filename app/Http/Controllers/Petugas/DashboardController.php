<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $petugasId = Auth::id();
        
        // Stats
        $todayBookings = Booking::where('petugas_id', $petugasId)
            ->whereDate('scheduled_date', today())
            ->count();
            
        $activeBookings = Booking::where('petugas_id', $petugasId)
            ->whereIn('status', ['scheduled', 'checked_in', 'in_progress'])
            ->count();
            
        $completedBookings = Booking::where('petugas_id', $petugasId)
            ->where('status', 'completed')
            ->count();
        
        // Recent bookings
        $recentBookings = Booking::where('petugas_id', $petugasId)
            ->with(['customer', 'service', 'packagePurchase.bookings', 'packagePurchase.package'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('petugas.dashboard', compact(
            'todayBookings',
            'activeBookings',
            'completedBookings',
            'recentBookings'
        ));
    }
}
