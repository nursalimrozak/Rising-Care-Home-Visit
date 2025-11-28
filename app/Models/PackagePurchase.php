<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PackagePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_id',
        'package_id',
        'total_visits',
        'used_visits',
        'purchased_at',
        'expires_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_visits' => 'integer',
            'used_visits' => 'integer',
            'purchased_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function package()
    {
        return $this->belongsTo(ServicePackage::class, 'package_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'package_purchase_id');
    }

    // Helper methods
    public function getRemainingVisits()
    {
        // Calculate used visits dynamically based on completed treatments
        // This fixes the issue where legacy data has used_visits=1 but no treatment is done
        $realUsedVisits = $this->bookings()->whereIn('status', ['completed', 'pending_payment'])->count();
        return $this->total_visits - $realUsedVisits;
    }

    public function hasRemainingVisits()
    {
        return $this->getRemainingVisits() > 0;
    }

    public function isExpired()
    {
        if (!$this->expires_at) {
            return false;
        }
        return Carbon::now()->isAfter($this->expires_at);
    }

    public function isActive()
    {
        return $this->status === 'active' && !$this->isExpired() && $this->hasRemainingVisits();
    }

    public function useVisit()
    {
        $this->increment('used_visits');
        
        if ($this->getRemainingVisits() <= 0) {
            $this->update(['status' => 'completed']);
        }
    }

    public function canBeUsed()
    {
        return $this->isActive();
    }
}
