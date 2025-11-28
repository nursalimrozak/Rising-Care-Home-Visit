<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'addon_id',
        'quantity',
        'price_per_item',
        'subtotal',
        'added_by',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price_per_item' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($bookingAddon) {
            $bookingAddon->subtotal = $bookingAddon->quantity * $bookingAddon->price_per_item;
        });

        // Stock management disabled for now
        // static::created(function ($bookingAddon) {
        //     $bookingAddon->addon->decrementStock($bookingAddon->quantity);
        // });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function addon()
    {
        return $this->belongsTo(ServiceAddon::class, 'addon_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
