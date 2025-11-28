<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommissionService
{
    /**
     * Calculate and distribute commissions for a paid payment.
     *
     * @param Payment $payment
     * @return void
     */
    public function calculateAndDistribute(Payment $payment)
    {
        // Prevent duplicate commissions
        if ($payment->commissions()->exists()) {
            return;
        }

        // Only process paid payments
        if ($payment->status !== 'paid') {
            return;
        }

        $paymentAmount = $payment->total_amount;
        $booking = $payment->booking;

        // Calculate proportions
        $servicePrice = $booking->estimated_price;
        $addonsPrice = $booking->bookingAddons->sum('subtotal');
        $totalExpected = $servicePrice + $addonsPrice;

        $commissionableAmount = 0;
        $addonRevenue = 0;

        if ($payment->payment_type === 'addon') {
            // If payment is specifically for add-ons, 100% goes to admin
            $commissionableAmount = 0;
            $addonRevenue = $paymentAmount;
        } else {
            // For full payment, dp, or settlement, split proportionally
            if ($totalExpected > 0) {
                $serviceRatio = $servicePrice / $totalExpected;
                $addonRatio = $addonsPrice / $totalExpected;

                $commissionableAmount = $paymentAmount * $serviceRatio;
                $addonRevenue = $paymentAmount * $addonRatio;
            } else {
                // Fallback if total expected is 0 (shouldn't happen for paid booking)
                $commissionableAmount = $paymentAmount;
                $addonRevenue = 0;
            }
        }

        // Define percentages from Site Settings (with fallback to defaults)
        $percentages = [
            'petugas' => (float) SiteSetting::get('commission_petugas', 60),
            'admin' => (float) SiteSetting::get('commission_admin', 20),
            'superadmin' => (float) SiteSetting::get('commission_superadmin', 15),
            'service' => (float) SiteSetting::get('commission_service', 5),
        ];

        DB::beginTransaction();

        try {
            // 1. Petugas Commission (From Base Service Only)
            $petugas = $booking->petugas;
            if ($petugas && $commissionableAmount > 0) {
                $this->createCommission($payment, $petugas, 'petugas', $commissionableAmount, $percentages['petugas']);
            }

            // 2. Admin Commission (From Base Service + 100% of Addons)
            // Admin gets their percentage of Base Service + 100% of Addons Revenue
            $admin = User::where('role', 'admin_staff')->where('is_active', true)->first();
            if ($admin) {
                // Admin base commission from service
                if ($commissionableAmount > 0) {
                    $adminBaseCommission = $commissionableAmount * ($percentages['admin'] / 100);
                } else {
                    $adminBaseCommission = 0;
                }
                
                // Total admin commission = base + all addon revenue
                $totalAdminAmount = $adminBaseCommission + $addonRevenue;
                
                if ($totalAdminAmount > 0) {
                    // Calculate effective percentage for the record
                    $effectiveAdminPercentage = ($paymentAmount > 0) ? ($totalAdminAmount / $paymentAmount) * 100 : 0;
                    
                    Commission::create([
                        'payment_id' => $payment->id,
                        'user_id' => $admin->id,
                        'role' => 'admin',
                        'amount' => $totalAdminAmount,
                        'percentage' => round($effectiveAdminPercentage, 2),
                        'status' => 'pending',
                    ]);
                }
            }

            // 3. Superadmin Commission (From Base Service Only)
            $superadmin = User::where('role', 'superadmin')->where('is_active', true)->first();
            if ($superadmin && $commissionableAmount > 0) {
                $this->createCommission($payment, $superadmin, 'superadmin', $commissionableAmount, $percentages['superadmin']);
            }

            // 4. Service Commission (Base Service Share Only - No Addons)
            // Service gets only their percentage of Base Service
            if ($commissionableAmount > 0) {
                $serviceAmount = $commissionableAmount * ($percentages['service'] / 100);
                
                Commission::create([
                    'payment_id' => $payment->id,
                    'user_id' => null, // Service / System
                    'role' => 'service',
                    'amount' => $serviceAmount,
                    'percentage' => $percentages['service'],
                    'status' => 'paid', // Service commission is considered paid/settled immediately
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to distribute commissions for payment ID {$payment->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create a single commission record.
     */
    private function createCommission(Payment $payment, ?User $user, string $role, float $totalAmount, float $percentage)
    {
        $commissionAmount = $totalAmount * ($percentage / 100);

        Commission::create([
            'payment_id' => $payment->id,
            'user_id' => $user ? $user->id : null,
            'role' => $role,
            'amount' => $commissionAmount,
            'percentage' => $percentage,
            'status' => 'pending',
        ]);
    }

    /**
     * Generate weekly payouts for all users with pending commissions.
     *
     * @return int Number of payouts generated
     */
    public function generateWeeklyPayouts()
    {
        $payoutFee = (float) SiteSetting::get('payout_fee', 5000);
        $generatedCount = 0;

        // Get all users with pending commissions
        // Group by user_id
        $pendingCommissions = Commission::where('status', 'pending')
            ->whereNotNull('user_id')
            ->get()
            ->groupBy('user_id');

        foreach ($pendingCommissions as $userId => $commissions) {
            $totalAmount = $commissions->sum('amount');

            // Check if total amount covers the fee
            if ($totalAmount <= $payoutFee) {
                continue; // Skip if not enough to cover fee
            }

            $netAmount = $totalAmount - $payoutFee;

            DB::transaction(function () use ($userId, $totalAmount, $payoutFee, $netAmount, $commissions) {
                // Create Payout
                $payout = Payout::create([
                    'user_id' => $userId,
                    'amount' => $totalAmount,
                    'fee' => $payoutFee,
                    'net_amount' => $netAmount,
                    'status' => 'pending',
                    'period_start' => $commissions->min('created_at'),
                    'period_end' => $commissions->max('created_at'),
                ]);

                // Update commissions
                foreach ($commissions as $commission) {
                    $commission->update([
                        'status' => 'paid', // Marked as paid (processed into a payout)
                        'payout_id' => $payout->id,
                    ]);
                }
            });

            $generatedCount++;
        }

        return $generatedCount;
    }
}
