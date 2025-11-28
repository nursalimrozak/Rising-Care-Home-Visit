<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_id',
        'customer_id',
        'petugas_id',
        'rating',
        'comment',
        'is_positive',
        'points_awarded',
        'show_on_landing',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'is_positive' => 'boolean',
            'points_awarded' => 'integer',
            'show_on_landing' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            // Auto-calculate is_positive
            $review->is_positive = $review->rating >= 4;
            
            // Award points for positive reviews
            if ($review->is_positive) {
                $review->points_awarded = config('loyalty.points_per_review', 100);
            }
        });

        static::created(function ($review) {
            if ($review->is_positive && $review->points_awarded > 0) {
                // Create loyalty transaction
                LoyaltyTransaction::create([
                    'customer_id' => $review->customer_id,
                    'booking_id' => $review->booking_id,
                    'type' => 'earned',
                    'points' => $review->points_awarded,
                    'description' => 'Poin dari ulasan positif',
                    'balance_after' => $review->customer->loyalty_points + $review->points_awarded,
                ]);

                // Update customer loyalty points
                $review->customer->increment('loyalty_points', $review->points_awarded);
            }
        });
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
