<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'service_type',
        'duration_minutes',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function prices()
    {
        return $this->hasMany(ServicePrice::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function packagePrices()
    {
        return $this->hasMany(PackagePrice::class);
    }

    public function getPriceForMembership($membershipId)
    {
        return $this->prices()->where('membership_id', $membershipId)->first()?->price ?? 0;
    }

    public function getPriceForPackageAndMembership($packageId, $membershipId)
    {
        return $this->packagePrices()
            ->where('package_id', $packageId)
            ->where('membership_id', $membershipId)
            ->first()?->price ?? 0;
    }
}
