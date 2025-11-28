<?php

use App\Models\Booking;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$bookingNumber = 'RS-20251124183138899';
$booking = Booking::where('booking_number', $bookingNumber)->with('payment', 'packagePurchase')->first();

if ($booking) {
    echo "Booking Found: " . $booking->booking_number . "\n";
    echo "Status: " . $booking->status . "\n";
    
    if ($booking->payment) {
        echo "Payment Found:\n";
        echo "  ID: " . $booking->payment->id . "\n";
        echo "  Status: " . $booking->payment->status . "\n";
        echo "  Type: " . $booking->payment->payment_type . "\n";
        echo "  Amount: " . $booking->payment->amount . "\n";
        echo "  Required: " . $booking->payment->required_amount . "\n";
        echo "  Paid At: " . ($booking->payment->paid_at ?? 'NULL') . "\n";
    } else {
        echo "No Payment Record Found.\n";
    }

    if ($booking->packagePurchase) {
        echo "Package Purchase Found:\n";
        echo "  Status: " . $booking->packagePurchase->status . "\n";
    }
} else {
    echo "Booking Not Found.\n";
}
