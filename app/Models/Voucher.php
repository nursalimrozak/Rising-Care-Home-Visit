<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_transaction',
        'usage_limit',
        'usage_count',
        'valid_from',
        'valid_until',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'min_transaction' => 'decimal:2',
            'usage_limit' => 'integer',
            'usage_count' => 'integer',
            'valid_from' => 'date',
            'valid_until' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function usages()
    {
        return $this->hasMany(VoucherUsage::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isValid()
    {
        return $this->is_active 
            && now()->between($this->valid_from, $this->valid_until)
            && ($this->usage_limit === null || $this->usage_count < $this->usage_limit);
    }

    public function canBeUsedFor($amount)
    {
        return $this->isValid() && ($this->min_transaction === null || $amount >= $this->min_transaction);
    }

    public function calculateDiscount($amount)
    {
        if (!$this->canBeUsedFor($amount)) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discount = $amount * ($this->discount_value / 100);
            if ($this->max_discount !== null) {
                $discount = min($discount, $this->max_discount);
            }
            return $discount;
        }

        return min($this->discount_value, $amount);
    }
}
