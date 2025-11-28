<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Membership extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'level',
        'discount_percentage',
        'description',
        'color',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'discount_percentage' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function occupations()
    {
        return $this->hasMany(Occupation::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function servicePrices()
    {
        return $this->hasMany(ServicePrice::class);
    }
}
