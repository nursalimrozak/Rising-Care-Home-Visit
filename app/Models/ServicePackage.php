<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'visit_count',
        'validity_weeks',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'visit_count' => 'integer',
            'validity_weeks' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function packagePrices()
    {
        return $this->hasMany(PackagePrice::class, 'package_id');
    }

    public function packagePurchases()
    {
        return $this->hasMany(PackagePurchase::class, 'package_id');
    }

    // Helper methods
    public function isMultiVisit()
    {
        return $this->visit_count > 1;
    }

    public function getValidityDays()
    {
        return $this->validity_weeks * 7;
    }

    public function isSingleVisit()
    {
        return $this->visit_count === 1;
    }
}
