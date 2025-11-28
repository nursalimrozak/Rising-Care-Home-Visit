<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        // Reguler bookings (single visit - no package)
        $regularBookings = Booking::with(['customer', 'service', 'petugas', 'payment'])
            ->whereNull('package_purchase_id')
            ->latest()
            ->paginate(10, ['*'], 'regular_page');

        // Eksekutif bookings (bookings with Eksekutif package)
        $eksekutifBookings = Booking::with(['customer', 'service', 'petugas', 'payment', 'packagePurchase.package'])
            ->whereHas('packagePurchase', function($q) {
                $q->whereHas('package', function($p) {
                    $p->where('name', 'Eksekutif');
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'eksekutif_page');

        // VIP bookings (bookings with VIP package)
        $vipBookings = Booking::with(['customer', 'service', 'petugas', 'payment', 'packagePurchase.package'])
            ->whereHas('packagePurchase', function($q) {
                $q->whereHas('package', function($p) {
                    $p->where('name', 'VIP');
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'vip_page');

        // Premium bookings (bookings with Premium package)
        $premiumBookings = Booking::with(['customer', 'service', 'petugas', 'payment', 'packagePurchase.package'])
            ->whereHas('packagePurchase', function($q) {
                $q->whereHas('package', function($p) {
                    $p->where('name', 'Premium');
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'premium_page');

        return view('admin.bookings.index', compact('regularBookings', 'eksekutifBookings', 'vipBookings', 'premiumBookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'service', 'petugas', 'payment']);
        $petugas = User::where('role', 'petugas')->get();
        return view('admin.bookings.show', compact('booking', 'petugas'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,scheduled,completed,cancelled',
        ]);

        $oldStatus = $booking->status;
        $booking->update(['status' => $validated['status']]);

        // Handle package visit count
        if ($booking->packagePurchase) {
            $preCompletionStatuses = ['pending', 'confirmed', 'scheduled', 'checked_in', 'in_progress'];
            $completionStatuses = ['pending_payment', 'completed'];

            // Increment if moving from Pre-Completion -> Completion
            if (in_array($oldStatus, $preCompletionStatuses) && in_array($validated['status'], $completionStatuses)) {
                $booking->packagePurchase->increment('used_visits');
            }
            // Decrement if moving from Completion -> Pre-Completion (Reverting)
            elseif (in_array($oldStatus, $completionStatuses) && in_array($validated['status'], $preCompletionStatuses)) {
                $booking->packagePurchase->decrement('used_visits');
            }
        }

        \App\Helpers\LogActivity::record(
            'UPDATE', 
            "Updated booking #{$booking->booking_number} status from {$oldStatus} to {$validated['status']}", 
            $booking
        );

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function assignPetugas(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'petugas_id' => 'required|exists:users,id',
        ]);

        $petugas = User::find($validated['petugas_id']);
        
        // Check availability for current booking
        if (!Booking::isPetugasAvailable($validated['petugas_id'], $booking->scheduled_date->format('Y-m-d'), $booking->scheduled_time->format('H:i:s'), $booking->id)) {
            return back()->withErrors(['petugas_id' => "Petugas {$petugas->name} sudah memiliki jadwal pada waktu ini (termasuk waktu perjalanan dan buffer)."]);
        }
        
        // Assign to current booking
        $booking->update(['petugas_id' => $validated['petugas_id']]);

        // If this booking is part of a package, check and assign to all visits
        if ($booking->packagePurchase) {
            $packageBookings = Booking::where('package_purchase_id', $booking->package_purchase_id)
                ->where('id', '!=', $booking->id)
                ->get();
            
            // First, check availability for all package bookings
            foreach ($packageBookings as $pkgBooking) {
                if (!Booking::isPetugasAvailable($validated['petugas_id'], $pkgBooking->scheduled_date->format('Y-m-d'), $pkgBooking->scheduled_time->format('H:i:s'), $pkgBooking->id)) {
                    // Rollback current booking assignment
                    $booking->update(['petugas_id' => null]);
                    
                    return back()->withErrors(['petugas_id' => "Petugas {$petugas->name} tidak tersedia untuk kunjungan #{$pkgBooking->visit_number} pada " . $pkgBooking->scheduled_date->format('d M Y') . " jam " . $pkgBooking->scheduled_time->format('H:i') . "."]);
                }
            }
            
            // All checks passed, assign to all package bookings
            foreach ($packageBookings as $pkgBooking) {
                $pkgBooking->update(['petugas_id' => $validated['petugas_id']]);
            }
            
            $totalAssigned = $packageBookings->count() + 1; // +1 for current booking
            
            \App\Helpers\LogActivity::record(
                'UPDATE', 
                "Assigned petugas {$petugas->name} to {$totalAssigned} bookings in package #{$booking->packagePurchase->id}", 
                $booking
            );
            
            return back()->with('success', "Petugas {$petugas->name} berhasil ditugaskan untuk {$totalAssigned} kunjungan dalam paket ini.");
        }

        // For non-package bookings (Reguler)
        \App\Helpers\LogActivity::record(
            'UPDATE', 
            "Assigned petugas {$petugas->name} to booking #{$booking->booking_number}", 
            $booking
        );

        return back()->with('success', 'Petugas berhasil ditugaskan.');
    }
}
