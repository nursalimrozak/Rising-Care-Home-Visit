<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_bookings_today' => Booking::whereDate('created_at', today())->count(),
            'total_bookings_week' => Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'total_bookings_month' => Booking::whereMonth('created_at', now()->month)->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'pending_payments' => Payment::whereIn('status', ['pending', 'pending_verification'])->count(),
            'total_revenue_month' => Payment::where('status', 'paid')
                ->whereMonth('paid_at', now()->month)
                ->sum('total_amount'),
        ];

        $recentBookings = Booking::with('customer', 'service', 'petugas')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $pendingPayments = Payment::with('booking.customer', 'booking.service')
            ->whereIn('status', ['pending', 'pending_verification'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'pendingPayments'));
    }
}
