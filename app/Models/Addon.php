<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Addon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'low_stock_threshold',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function isLowStock()
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    public function decrementStock($quantity)
    {
        $this->decrement('stock_quantity', $quantity);
    }

    public function incrementStock($quantity)
    {
        $this->increment('stock_quantity', $quantity);
    }
}
