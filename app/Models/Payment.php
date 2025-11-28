<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_number',
        'booking_id',
        'customer_id',
        'payment_method',
        'payment_type',
        'subtotal',
        'membership_discount',
        'voucher_discount',
        'points_discount',
        'points_redeemed',
        'total_amount',
        'required_amount',
        'status',
        'payment_proof',
        'final_payment_proof',
        'paid_at',
        'uploaded_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'membership_discount' => 'decimal:2',
            'voucher_discount' => 'decimal:2',
            'points_discount' => 'decimal:2',
            'points_redeemed' => 'integer',
            'total_amount' => 'decimal:2',
            'required_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = self::generatePaymentNumber();
            }
        });

        static::updated(function ($payment) {
            // Update booking status when payment is confirmed
            if ($payment->status === 'paid' && $payment->booking->status === 'completed') {
                // Payment completed - no additional action needed
            }
        });
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    public static function generatePaymentNumber(): string
    {
        $date = now()->format('Ymd');
        $random = str_pad(random_int(0, 999999999), 9, '0', STR_PAD_LEFT);
        return "PAY-{$date}{$random}";
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function voucherUsage()
    {
        return $this->hasOne(VoucherUsage::class);
    }

    public function loyaltyTransactions()
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isPendingVerification()
    {
        return $this->status === 'pending_verification';
    }

    public function isDP()
    {
        return $this->payment_type === 'dp';
    }

    public function isFullPayment()
    {
        return $this->payment_type === 'full';
    }

    public function getRemainingAmountAttribute()
    {
        if ($this->isFullPayment()) {
            return 0;
        }
        
        return max(0, $this->total_amount - $this->required_amount);
    }
}
