<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Occupation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $services = Service::where('is_active', true)->with('category')->get();
        $packages = \App\Models\ServicePackage::where('is_active', true)->orderBy('visit_count')->get();
        
        // Handle use_package parameter for booking next visit
        $packagePurchase = null;
        $minDate = now()->addDay()->format('Y-m-d'); // Default: tomorrow
        $maxDate = null; // Will be set if using package with expiry
        $lastBookingDate = null;
        
        if ($request->has('use_package')) {
            // Decode base64 encoded package ID
            $packageId = base64_decode($request->use_package);
            
            // Validate decoded ID is numeric
            if (!is_numeric($packageId)) {
                return redirect()->route('customer.packages.index')
                    ->with('error', 'Invalid package parameter.');
            }
            
            $packagePurchase = \App\Models\PackagePurchase::with(['package', 'service', 'bookings'])
                ->where('id', $packageId)
                ->where('customer_id', Auth::id())
                ->first();
            
            if ($packagePurchase) {
                // Check if package can still be used
                if (!$packagePurchase->canBeUsed()) {
                    return redirect()->route('customer.packages.show', $packagePurchase->id)
                        ->with('error', 'Paket tidak dapat digunakan. Pastikan paket masih aktif dan memiliki sisa kunjungan.');
                }
                
                // Set max date based on package expiry
                if ($packagePurchase->expires_at) {
                    $maxDate = $packagePurchase->expires_at->format('Y-m-d');
                }
                
                // Get last booking date to set minimum date for next visit
                $lastBooking = $packagePurchase->bookings()
                    ->orderBy('scheduled_date', 'desc')
                    ->first();
                
                if ($lastBooking) {
                    $lastBookingDate = $lastBooking->scheduled_date;
                    // Next visit must be after last booking date
                    $minDate = \Carbon\Carbon::parse($lastBookingDate)->addDay()->format('Y-m-d');
                }
            } else {
                return redirect()->route('customer.packages.index')
                    ->with('error', 'Paket tidak ditemukan atau bukan milik Anda.');
            }
        }
        
        return view('booking.create', compact('services', 'packages', 'packagePurchase', 'minDate', 'maxDate', 'lastBookingDate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'package_id' => 'required|exists:service_packages,id',
            'booking_type' => 'required|in:on_site,home_visit',
            'scheduled_date' => 'required|date|after:today',
            'scheduled_time' => 'required',
            'customer_address' => 'required_if:booking_type,home_visit',
            'customer_notes' => 'nullable|string',
            'use_existing_package' => 'nullable|exists:package_purchases,id',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $service = Service::find($validated['service_id']);
            $package = \App\Models\ServicePackage::find($validated['package_id']);
            
            // Get price for selected package and user's membership
            $estimatedPrice = $service->getPriceForPackageAndMembership($package->id, $user->membership_id);
            
            $packagePurchaseId = null;
            $visitNumber = 1;
            
            // Check if using existing package or creating new one
            if ($request->filled('use_existing_package')) {
                // Use existing package purchase
                $packagePurchase = \App\Models\PackagePurchase::find($validated['use_existing_package']);
                
                if (!$packagePurchase->canBeUsed()) {
                    throw new \Exception('Paket tidak dapat digunakan (sudah habis atau kadaluarsa)');
                }
                
                // Validate booking date is after last booking
                $lastBooking = $packagePurchase->bookings()
                    ->orderBy('scheduled_date', 'desc')
                    ->first();
                
                if ($lastBooking) {
                    $scheduledDate = \Carbon\Carbon::parse($validated['scheduled_date']);
                    $lastBookingDate = \Carbon\Carbon::parse($lastBooking->scheduled_date);
                    
                    if ($scheduledDate->lte($lastBookingDate)) {
                        DB::rollBack();
                        return back()
                            ->withErrors(['scheduled_date' => 'Tanggal booking harus setelah booking terakhir (' . $lastBookingDate->format('d M Y') . ')'])
                            ->withInput();
                    }
                }
                
                // Validate booking date is before package expiry
                if ($packagePurchase->expires_at) {
                    $scheduledDate = \Carbon\Carbon::parse($validated['scheduled_date']);
                    $expiryDate = \Carbon\Carbon::parse($packagePurchase->expires_at);
                    
                    if ($scheduledDate->gt($expiryDate)) {
                        DB::rollBack();
                        return back()
                            ->withErrors(['scheduled_date' => 'Tanggal booking tidak boleh melebihi masa aktif paket (' . $expiryDate->format('d M Y') . ')'])
                            ->withInput();
                    }
                }
                
                $packagePurchaseId = $packagePurchase->id;
                $visitNumber = $packagePurchase->used_visits + 1;
                
            } elseif ($package->isMultiVisit()) {
                // Check if customer already has an active package for this service
                $activePackage = \App\Models\PackagePurchase::where('customer_id', $user->id)
                    ->where('service_id', $service->id)
                    ->whereIn('status', ['active', 'pending'])
                    ->whereRaw('(total_visits - used_visits) > 0')
                    ->where(function($query) {
                        $query->whereNull('expires_at')
                              ->orWhere('expires_at', '>', now());
                    })
                    ->first();
                
                if ($activePackage) {
                    DB::rollBack();
                    return back()
                        ->with('error', 'Anda masih memiliki paket aktif untuk layanan ini. Silakan gunakan paket yang ada atau tunggu hingga paket selesai.')
                        ->with('show_modal', true)
                        ->withInput();
                }
                
                // Create new package purchase for multi-visit packages
                // Expiry will be calculated from first visit scheduled date
                $expiryDate = null;
                if ($package->validity_weeks > 0) {
                    $expiryDate = \Carbon\Carbon::parse($validated['scheduled_date'])->addWeeks($package->validity_weeks);
                }
                
                $packagePurchase = \App\Models\PackagePurchase::create([
                    'customer_id' => $user->id,
                    'service_id' => $service->id,
                    'package_id' => $package->id,
                    'total_visits' => $package->visit_count,
                    'used_visits' => 0, // Count only updates when treatment is completed
                    'purchased_at' => now(),
                    'expires_at' => $expiryDate,
                    'status' => 'pending',
                ]);
                
                $packagePurchaseId = $packagePurchase->id;
                // Visit number is calculated dynamically in view, but we store it for reference
                // For new package, this is the 1st booking
                $visitNumber = 1; 
            } elseif ($package->isMultiVisit()) {
                // Check if customer already has an active package for this service
                $activePackage = \App\Models\PackagePurchase::where('customer_id', $user->id)
                    ->where('service_id', $service->id)
                    ->whereIn('status', ['active', 'pending'])
                    ->first();

                // Validate quota manually
                if ($activePackage) {
                    $activeBookingsCount = $activePackage->bookings()
                        ->whereIn('status', ['pending_payment', 'scheduled', 'confirmed', 'in_progress'])
                        ->count();
                    
                    $remainingQuota = $activePackage->total_visits - $activePackage->used_visits - $activeBookingsCount;

                    if ($remainingQuota <= 0) {
                        DB::rollBack();
                        return back()
                            ->with('error', 'Kuota kunjungan paket Anda sudah habis atau sedang terjadwal semua.')
                            ->with('show_modal', true)
                            ->withInput();
                    }
                    
                    // Check expiry
                    if ($activePackage->expires_at && $activePackage->expires_at->isPast()) {
                         DB::rollBack();
                         return back()
                            ->with('error', 'Paket Anda sudah kadaluarsa.')
                            ->with('show_modal', true)
                            ->withInput();
                    }
                }
                
                // ... (rest of logic)
            }
            // For single-visit (Reguler), no package purchase needed

            // Determine booking status and payment requirement
            $isUsingExistingPackage = $request->filled('use_existing_package');
            $bookingStatus = $isUsingExistingPackage ? 'scheduled' : 'pending_payment';
            
            // Calculate required payment amount (only for new bookings, not existing package)
            $requiredAmount = $package->isMultiVisit() ? $estimatedPrice : 100000; // DP Rp 100k for Reguler

            // Auto-assign staff if using existing package and previous staff exists
            $assignedPetugasId = null;
            if ($isUsingExistingPackage) {
                $packagePurchase = \App\Models\PackagePurchase::find($validated['use_existing_package']);
                $lastBookingWithStaff = $packagePurchase->bookings()
                    ->whereNotNull('petugas_id')
                    ->latest()
                    ->first();
                
                if ($lastBookingWithStaff) {
                    $assignedPetugasId = $lastBookingWithStaff->petugas_id;
                }
            }

            // Create booking
            $booking = Booking::create([
                'customer_id' => $user->id,
                'service_id' => $validated['service_id'],
                'package_purchase_id' => $packagePurchaseId,
                'visit_number' => $visitNumber, // This might be legacy, view uses dynamic calc
                'created_by' => $user->id,
                'booking_type' => $validated['booking_type'],
                'scheduled_date' => $validated['scheduled_date'],
                'scheduled_time' => $validated['scheduled_time'],
                'customer_address' => $validated['customer_address'] ?? null,
                'customer_notes' => $validated['customer_notes'] ?? null,
                'status' => $bookingStatus,
                'estimated_price' => $estimatedPrice,
                'petugas_id' => $assignedPetugasId, // Auto-assign staff
            ]);
            
            // NOTE: used_visits is NOT incremented here anymore. 
            // It should be incremented when booking status becomes 'completed'.
            
            // Create payment record only for new package purchases (not existing package bookings)
            if (!$isUsingExistingPackage) {
                \App\Models\Payment::create([
                    'booking_id' => $booking->id,
                    'customer_id' => $user->id,
                    'payment_method' => 'transfer',
                    'payment_type' => $package->isMultiVisit() ? 'full' : 'dp',
                    'subtotal' => $estimatedPrice,
                    'total_amount' => $estimatedPrice,
                    'required_amount' => $requiredAmount,
                    'status' => 'pending',
                ]);
            }
            
            DB::commit();

            // Redirect based on booking type
            if ($isUsingExistingPackage) {
                // For existing package bookings, redirect to package detail
                $packagePurchase = \App\Models\PackagePurchase::find($validated['use_existing_package']);
                return redirect()->route('customer.packages.show', $packagePurchase->id)
                    ->with('success', 'Jadwal kunjungan berhasil dibuat! Silakan tunggu konfirmasi dari admin.');
            } else {
                // For new package purchases, redirect to success page with payment info
                return redirect()->route('booking.success', $booking->booking_number)
                    ->with('success', 'Booking berhasil dibuat! Silakan upload bukti pembayaran.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function success($bookingNumber)
    {
        $booking = Booking::where('booking_number', $bookingNumber)
            ->with('service', 'customer', 'packagePurchase.package')
            ->firstOrFail();

        return view('booking.success', compact('booking'));
    }

    public function myBookings()
    {
        $userId = Auth::id();
        
        // Get IDs of first bookings for each package (based on created_at)
        $firstPackageBookingIds = \DB::table('bookings as b1')
            ->select('b1.id')
            ->whereNotNull('b1.package_purchase_id')
            ->where('b1.customer_id', $userId)
            ->whereRaw('b1.created_at = (
                SELECT MIN(b2.created_at) 
                FROM bookings b2 
                WHERE b2.package_purchase_id = b1.package_purchase_id
            )')
            ->pluck('id');
        
        $bookings = Auth::user()->bookingsAsCustomer()
            ->with('service', 'petugas', 'packagePurchase')
            // Show: regular bookings OR first booking of each package
            ->where(function($query) use ($firstPackageBookingIds) {
                $query->whereNull('package_purchase_id') // Regular bookings without package
                      ->orWhereIn('id', $firstPackageBookingIds); // First booking of each package
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('booking.my-bookings', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        // Ensure user owns the booking
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['packagePurchase.package', 'payment']);
        
        // Get active bank accounts for payment
        $bankAccounts = \App\Models\BankAccount::active()->ordered()->get();
        
        return view('booking.show', compact('booking', 'bankAccounts'));
    }

    public function cancel(Booking $booking)
    {
        // Ensure user owns the booking
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }

        // Check if booking can be cancelled
        if (!in_array($booking->status, ['scheduled', 'pending_payment'])) {
            return back()->with('error', 'Booking tidak dapat dibatalkan karena statusnya sudah ' . $booking->status);
        }

        DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'cancelled']);

            // Handle Package Logic
            if ($booking->packagePurchase) {
                if ($booking->visit_number == 1) {
                    // This is the purchase booking. Cancel the package.
                    $booking->packagePurchase->update(['status' => 'cancelled']);
                } elseif ($booking->getOriginal('status') === 'completed') {
                    // Only restore visit if booking was previously completed
                    $booking->packagePurchase->decrement('used_visits');
                    
                    // If package was completed, make it active again
                    if ($booking->packagePurchase->status == 'completed') {
                        $booking->packagePurchase->update(['status' => 'active']);
                    }
                }
            }
            
            // Cancel payment if it exists and is pending
            if ($booking->payment && $booking->payment->status == 'pending') {
                $booking->payment->update(['status' => 'failed']); // Use failed as cancelled
            }
        });

        return redirect()->route('booking.show', $booking)
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    public function storeReview(Request $request, Booking $booking)
    {
        // Ensure user owns the booking
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }

        // Check if booking is completed
        if ($booking->status !== 'completed') {
            return back()->with('error', 'Ulasan hanya dapat diberikan untuk booking yang sudah selesai.');
        }

        // Check if review already exists
        if ($booking->review) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk booking ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        \App\Models\Review::create([
            'booking_id' => $booking->id,
            'customer_id' => Auth::id(),
            'petugas_id' => $booking->petugas_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('booking.show', $booking)
            ->with('success', 'Terima kasih! Ulasan Anda telah berhasil dikirim.');
    }

    public function uploadPaymentProof(Request $request, Booking $booking)
    {
        // Ensure user owns the booking
        if ($booking->customer_id !== Auth::id()) {
            abort(403);
        }

        // Validate payment proof
        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Get payment record
        $payment = $booking->payment;
        
        if (!$payment) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Check if we can upload (Pending OR (DP Paid AND not yet verified full))
        $canUpload = $payment->status == 'pending' || 
                     ($payment->payment_type == 'dp' && $payment->status == 'paid');

        if (!$canUpload) {
            return back()->with('error', 'Pembayaran tidak dapat diproses saat ini.');
        }

        // Store payment proof
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = 'payment_' . $booking->booking_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment_proofs', $filename, 'public');

            // Update payment record
            if ($payment->status == 'pending') {
                // Initial Payment
                $payment->update([
                    'payment_proof' => $path,
                    'status' => 'pending_verification',
                    'uploaded_by' => Auth::id(),
                ]);
            } else {
                // Final Payment (Pelunasan)
                $payment->update([
                    'final_payment_proof' => $path,
                    'status' => 'pending_verification', // Set back to pending verification
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('booking.show', $booking->booking_number)
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }
}
