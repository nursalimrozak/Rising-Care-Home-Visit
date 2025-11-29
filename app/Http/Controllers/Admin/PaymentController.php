<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['booking.customer', 'booking.service'])->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.customer', 'booking.service']);
        return view('admin.payments.show', compact('payment'));
    }

    public function uploadProof(Request $request, Payment $payment)
    {
        $request->validate([
            'proof_image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('proof_image')) {
            // Delete old proof if exists
            if ($payment->proof_image) {
                Storage::disk('public')->delete($payment->proof_image);
            }

            // Store in 'buktipembayaran' folder
            $path = $request->file('proof_image')->store('buktipembayaran', 'public');
            
            $payment->update([
                'proof_image' => $path,
                'status' => 'pending_verification', // Update status if needed
                'uploaded_at' => now(),
            ]);

            \App\Helpers\LogActivity::record('UPDATE', "Uploaded payment proof for payment #{$payment->id}", $payment);

            return back()->with('success', 'Bukti pembayaran berhasil diupload.');
        }

        return back()->with('error', 'Gagal mengupload bukti pembayaran.');
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,failed,refunded',
            'admin_note' => 'nullable|string',
        ]);

        $oldStatus = $payment->status;
        $payment->update($validated);

        \App\Helpers\LogActivity::record(
            'UPDATE', 
            "Updated payment #{$payment->id} status from {$oldStatus} to {$validated['status']}", 
            $payment
        );

        return back()->with('success', 'Status pembayaran diperbarui.');
    }

    public function verify(Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update booking status if it was pending payment
        if ($payment->booking->status === 'pending_payment') {
            // Payment verified, but booking stays in pending_payment until petugas finalizes
            // Only change to scheduled if this is initial payment (no completed_at yet)
            if (!$payment->booking->completed_at) {
                $payment->booking->update(['status' => 'scheduled']);
            }
            // If completed_at exists, petugas has finished treatment
            // Keep status as pending_payment until petugas clicks "Selesaikan Treatment" (finalize)
        }

        // Activate package if this is a full payment for a package
        if ($payment->isFullPayment() && $payment->booking->packagePurchase) {
            $payment->booking->packagePurchase->update(['status' => 'active']);
        }

        \App\Helpers\LogActivity::record('UPDATE', "Verified payment #{$payment->payment_number}", $payment);

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $payment->update([
            'status' => 'failed', // Set to failed so user knows it was rejected
            'notes' => $request->rejection_reason,
        ]);

        \App\Helpers\LogActivity::record('UPDATE', "Rejected payment #{$payment->payment_number}. Reason: {$request->rejection_reason}", $payment);

        return back()->with('success', 'Pembayaran ditolak.');
    }
}
