<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Scope to get only active bank accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('id');
    }

    /**
     * Get formatted account number with masking
     */
    public function getMaskedAccountNumberAttribute()
    {
        $number = $this->account_number;
        $length = strlen($number);
        
        if ($length <= 4) {
            return $number;
        }
        
        return str_repeat('*', $length - 4) . substr($number, -4);
    }
}
