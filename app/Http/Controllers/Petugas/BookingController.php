<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServiceAddon;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('petugas_id', Auth::id())
            ->with(['customer', 'service', 'packagePurchase.bookings', 'packagePurchase.package'])
            ->latest()
            ->paginate(15);
            
        return view('petugas.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        // Ensure petugas owns the booking
        if ($booking->petugas_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['customer', 'service', 'bookingAddons.addon', 'payment', 'packagePurchase.bookings', 'packagePurchase.package']);
        $availableAddons = ServiceAddon::where('is_active', true)->get();
        $bankAccounts = \App\Models\BankAccount::active()->ordered()->get();
        
        return view('petugas.bookings.show', compact('booking', 'availableAddons', 'bankAccounts'));
    }

    public function checkin(Booking $booking)
    {
        // Ensure petugas owns the booking
        if ($booking->petugas_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'scheduled') {
            return back()->with('error', 'Booking tidak dapat di-check-in.');
        }

        $booking->update([
            'status' => 'checked_in',
            'checked_in_at' => now(),
        ]);

        return back()->with('success', 'Customer berhasil check-in.');
    }

    public function start(Booking $booking)
    {
        // Ensure petugas owns the booking
        if ($booking->petugas_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['scheduled', 'checked_in'])) {
            return back()->with('error', 'Booking tidak dapat dimulai.');
        }

        // Check if this is a package booking and if previous visit is completed
        if ($booking->package_purchase_id && $booking->packagePurchase) {
            $allBookings = $booking->packagePurchase->bookings()->orderBy('created_at')->get();
            $currentIndex = $allBookings->search(function($b) use ($booking) {
                return $b->id === $booking->id;
            });
            
            // If not the first booking, check if previous booking is completed
            if ($currentIndex > 0) {
                $previousBooking = $allBookings[$currentIndex - 1];
                if (!in_array($previousBooking->status, ['completed', 'pending_payment'])) {
                    return back()->with('error', 'Kunjungan sebelumnya harus diselesaikan terlebih dahulu.');
                }
            }
        }

        $booking->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return back()->with('success', 'Treatment dimulai. Timer berjalan.');
    }

    public function addAddon(Request $request, Booking $booking)
    {
        // Ensure petugas owns the booking
        if ($booking->petugas_id !== Auth::id()) {
            abort(403);
        }

        // Allow adding add-ons during pending_payment (after treatment completed)
        if ($booking->status !== 'pending_payment') {
            return back()->with('error', 'Add-on hanya dapat ditambahkan setelah treatment selesai.');
        }

        $validated = $request->validate([
            'addons' => 'required|array|min:1',
            'addons.*.addon_id' => 'required|exists:service_addons,id',
            'addons.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($validated['addons'] as $addonData) {
            $addon = ServiceAddon::findOrFail($addonData['addon_id']);

            $booking->bookingAddons()->create([
                'addon_id' => $addon->id,
                'quantity' => $addonData['quantity'],
                'price_per_item' => $addon->price,
                'subtotal' => $addon->price * $addonData['quantity'],
                'added_by' => Auth::id(),
            ]);
        }

        return back()->with('success', count($validated['addons']) . ' add-on berhasil ditambahkan.');
    }

    public function complete(Booking $booking)
    {
        // Ensure petugas owns the booking
        if ($booking->petugas_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'in_progress') {
            return back()->with('error', 'Booking tidak dapat diselesaikan.');
        }

        // Calculate duration
        $duration = $booking->started_at ? now()->diffInMinutes($booking->started_at) : 0;

        // Determine next status
        $nextStatus = 'pending_payment';
        
        // Check if package is paid and no addons
        if ($booking->packagePurchase && in_array($booking->packagePurchase->status, ['active', 'completed'])) {
            $hasAddons = $booking->bookingAddons()->count() > 0;
            if (!$hasAddons) {
                $nextStatus = 'completed';
            }
        }

        $booking->update([
            'status' => $nextStatus,
            'completed_at' => now(),
            'duration_minutes' => $duration,
        ]);

        // Increment used visits for package
        if ($booking->packagePurchase) {
            $booking->packagePurchase->increment('used_visits');
        }

        return back()->with('success', 'Treatment selesai. Durasi: ' . $duration . ' menit. Silakan proses pembayaran.');
    }

    public function processPayment(Request $request, Booking $booking)
    {
        // Ensure petugas owns the booking
        if ($booking->petugas_id !== Auth::id()) {
            abort(403);
        }

        // Allow payment processing if pending_payment OR if it's a DP payment that needs final settlement
        $isFinalPayment = false;
        if ($booking->payment && $booking->payment->payment_type == 'dp') {
            if ($booking->payment->status == 'paid') {
                $isFinalPayment = true;
            } elseif ($booking->status == 'pending_payment' && $booking->payment->payment_proof) {
                // If treatment is done and we already have a DP proof, assume this is final payment
                // even if DP status is not 'paid' yet (to prevent overwriting DP proof)
                $isFinalPayment = true;
            }
        } elseif ($booking->status !== 'pending_payment') {
            return back()->with('error', 'Pembayaran hanya dapat diproses setelah treatment selesai.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,transfer',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        // Calculate total amount
        $servicePrice = $booking->estimated_price;
        $addonsTotal = $booking->bookingAddons->sum('subtotal');
        $totalAmount = $servicePrice + $addonsTotal;

        // Handle proof image upload
        $proofPath = null;
        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')->store('payment-proofs', 'public');
        }

        if ($booking->payment) {
            // Update existing payment record
            $payment = $booking->payment;
            
            if ($isFinalPayment) {
                // Final Settlement (DP -> Full)
                $paymentData = [
                    'total_amount' => $totalAmount,
                    'uploaded_by' => Auth::id(),
                    'status' => 'pending', // Reset status to pending for verification
                ];
                
                if ($proofPath) {
                    $paymentData['final_payment_proof'] = $proofPath;
                }
                
                $payment->update($paymentData);
                $message = 'Bukti pelunasan berhasil diupload. Menunggu verifikasi admin.';
            } else {
                // Updating Initial Payment (Pending/Failed) or Retry
                $paymentData = [
                    'payment_method' => $validated['payment_method'],
                    'subtotal' => $servicePrice,
                    'total_amount' => $totalAmount,
                    'uploaded_by' => Auth::id(),
                ];

                if ($proofPath) {
                    $paymentData['payment_proof'] = $proofPath;
                }

                if ($validated['payment_method'] === 'cash') {
                    $paymentData['status'] = 'paid';
                    $paymentData['paid_at'] = now();
                    
                    // Update booking to completed
                    $booking->update([
                        'status' => 'completed',
                        'final_price' => $totalAmount,
                    ]);
                    
                    $message = 'Pembayaran cash berhasil diproses. Booking selesai.';
                } else {
                    $paymentData['status'] = 'pending'; // Set to pending for verification
                    $message = 'Bukti transfer berhasil diupload. Menunggu verifikasi.';
                }
                
                $payment->update($paymentData);
            }
        } else {
            // Create new payment record
            $paymentData = [
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'payment_method' => $validated['payment_method'],
                'subtotal' => $servicePrice,
                'total_amount' => $totalAmount,
                'uploaded_by' => Auth::id(),
            ];

            if ($proofPath) {
                $paymentData['payment_proof'] = $proofPath;
            }

            // Set status based on payment method
            if ($validated['payment_method'] === 'cash') {
                $paymentData['status'] = 'paid';
                $paymentData['paid_at'] = now();
                
                // Update booking to completed
                $booking->update([
                    'status' => 'completed',
                    'final_price' => $totalAmount,
                ]);
                
                $message = 'Pembayaran cash berhasil diproses. Booking selesai.';
            } else {
                $paymentData['status'] = 'pending';
                $message = 'Bukti transfer berhasil diupload. Menunggu verifikasi.';
            }

            Payment::create($paymentData);
        }

        return back()->with('success', $message);
    }

    public function verifyPayment(Request $request, Booking $booking)
    {
        // Ensure petugas owns the booking
        if ($booking->petugas_id !== Auth::id()) {
            abort(403);
        }

        $payment = $booking->payment;

        if (!$payment || $payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran tidak dapat diverifikasi.');
        }

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Do NOT complete booking here. Petugas must click "Selesai" manually.
        // Just update final price just in case
        $booking->update([
            'final_price' => $payment->total_amount,
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function uploadAddonPayment(Request $request, Booking $booking)
    {
        // Ensure petugas owns the booking
        if ($booking->petugas_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,transfer',
            'addon_proof_image' => 'required_if:payment_method,transfer|image|max:2048',
        ]);

        $totalAddons = $booking->bookingAddons->sum('subtotal');
        
        if ($totalAddons <= 0) {
            return back()->with('error', 'Tidak ada tagihan add-ons.');
        }

        $proofPath = null;
        if ($request->hasFile('addon_proof_image')) {
            $proofPath = $request->file('addon_proof_image')->store('payment_proofs', 'public');
        }

        // Check if payment record exists
        $payment = $booking->payment;

        $paymentData = [
            'booking_id' => $booking->id,
            'customer_id' => $booking->customer_id,
            'payment_method' => $validated['payment_method'],
            'subtotal' => 0, // Service price is 0 for addon-only payment
            'total_amount' => $totalAddons,
            'payment_type' => 'addon',
            'uploaded_by' => Auth::id(),
        ];

        if ($proofPath) {
            $paymentData['addon_payment_proof'] = $proofPath;
            $paymentData['status'] = 'pending_verification';
        } elseif ($validated['payment_method'] === 'cash') {
            $paymentData['status'] = 'paid';
            $paymentData['paid_at'] = now();
        }

        if ($payment) {
            $payment->update($paymentData);
        } else {
            Payment::create($paymentData);
        }

        return back()->with('success', 'Pembayaran add-ons berhasil diproses.');
    }

    public function finalize(Booking $booking)
    {
        // Ensure petugas owns the booking
        if ($booking->petugas_id !== Auth::id()) {
            abort(403);
        }

        // Check payment status
        $isPaid = false;

        // 1. Check if package booking
        if ($booking->packagePurchase && in_array($booking->packagePurchase->status, ['active', 'completed'])) {
            // Package is paid, now check add-ons
            $unpaidAddons = $booking->bookingAddons->sum('subtotal');
            
            if ($unpaidAddons > 0) {
                // If has add-ons, check if they are paid
                if ($booking->payment && $booking->payment->status === 'paid' && $booking->payment->payment_type === 'addon') {
                    $isPaid = true;
                }
            } else {
                // No add-ons, package is paid -> OK
                $isPaid = true;
            }
        } 
        // 2. Regular booking or first package visit with payment
        elseif ($booking->payment && $booking->payment->status === 'paid') {
            // Check if it's full payment or DP with settlement
            if ($booking->payment->payment_type === 'full') {
                $isPaid = true;
            } elseif ($booking->payment->payment_type === 'dp' && $booking->payment->final_payment_proof) {
                // Assuming admin verifies final payment proof, but for now let's allow if proof uploaded
                // Ideally status should be 'paid' after admin verification
                $isPaid = true; 
            }
        }

        if (!$isPaid) {
            return back()->with('error', 'Booking tidak dapat diselesaikan. Pembayaran belum lunas.');
        }

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(), // Update completed_at again if needed
        ]);

        return back()->with('success', 'Booking berhasil diselesaikan.');
    }

    public function removeAddon(\App\Models\BookingAddon $bookingAddon)
    {
        $booking = $bookingAddon->booking;

        // Ensure petugas owns the booking
        if ($booking->petugas_id !== Auth::id()) {
            abort(403);
        }

        // Ensure booking is editable (pending payment or paid DP)
        if (!($booking->status == 'pending_payment' || ($booking->payment && $booking->payment->status == 'paid' && $booking->payment->payment_type == 'dp'))) {
            return back()->with('error', 'Add-on tidak dapat dihapus pada status ini.');
        }

        $bookingAddon->delete();

        return back()->with('success', 'Add-on berhasil dihapus.');
    }
}
