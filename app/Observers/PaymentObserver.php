<?php

namespace App\Observers;

use App\Models\Payment;
use App\Services\CommissionService;

class PaymentObserver
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        // Check if status changed to 'paid'
        if ($payment->isDirty('status') && $payment->status === 'paid') {
            $this->commissionService->calculateAndDistribute($payment);
        }
    }
}
