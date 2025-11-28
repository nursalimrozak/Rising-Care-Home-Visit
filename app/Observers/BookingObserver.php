<?php

namespace App\Observers;

use App\Models\Booking;

class BookingObserver
{
    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // Check if status changed to completed
        if ($booking->isDirty('status') && $booking->status === 'completed') {
            // Increment used_visits for package purchase
            if ($booking->packagePurchase) {
                $booking->packagePurchase->useVisit();
            }
        }
        
        // Handle cancellation - restore visit if was previously completed
        if ($booking->isDirty('status') && $booking->status === 'cancelled') {
            $originalStatus = $booking->getOriginal('status');
            
            // Only restore visit if booking was previously completed
            if ($originalStatus === 'completed' && $booking->packagePurchase) {
                $booking->packagePurchase->decrement('used_visits');
                
                // If package was marked as completed, make it active again
                if ($booking->packagePurchase->status === 'completed' && $booking->packagePurchase->getRemainingVisits() > 0) {
                    $booking->packagePurchase->update(['status' => 'active']);
                }
            }
        }
    }
}
