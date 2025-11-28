<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function show(Booking $booking)
    {
        // Ensure the booking belongs to the authenticated customer
        if ($booking->customer_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // Ensure this is a package booking
        if (!$booking->packagePurchase) {
            return redirect()->route('booking.show', $booking->booking_number)
                ->with('info', 'This is not a package booking. Redirected to regular booking detail.');
        }

        // Get bank accounts for payment
        $bankAccounts = BankAccount::where('is_active', true)->get();

        // Calculate payment details
        $isPackageBooking = true;
        $addonsTotal = $booking->bookingAddons->sum('subtotal');
        
        // For package bookings, check if it's a new purchase or existing usage
        $totalAmount = $addonsTotal;
        $paidAmount = 0;
        
        // If package is pending (new purchase), include package price
        if ($booking->packagePurchase->status == 'pending') {
            // Get package price from payment record if exists, or calculate it
            if ($booking->payment) {
                $totalAmount += $booking->payment->required_amount;
            } else {
                // Fallback if payment record missing (should not happen for new purchase)
                $totalAmount += $booking->estimated_price;
            }
        }
        
        // Check if add-ons have been paid
        if ($booking->payment && $booking->payment->status == 'paid') {
            // If paid, assume package part is settled, check addons
            if ($addonsTotal > 0) {
                $paidAmount = $addonsTotal;
            }
        }
        
        // Calculate remaining amount
        if ($booking->packagePurchase->status == 'pending') {
            if ($booking->payment) {
                $remainingAmount = $booking->payment->required_amount;
            } else {
                $remainingAmount = $totalAmount;
            }
        } else {
            // For active packages, only addons need payment
            $remainingAmount = $addonsTotal > 0 ? ($addonsTotal - $paidAmount) : 0;
        }

        return view('customer.treatment.show', compact(
            'booking',
            'bankAccounts',
            'isPackageBooking',
            'totalAmount',
            'paidAmount',
            'remainingAmount',
            'addonsTotal'
        ));
    }
}
