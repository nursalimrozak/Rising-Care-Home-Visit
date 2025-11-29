<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'customer_id',
        'service_id',
        'package_purchase_id',
        'visit_number',
        'petugas_id',
        'created_by',
        'booking_type',
        'scheduled_date',
        'scheduled_time',
        'customer_address',
        'customer_notes',
        'status',
        'estimated_price',
        'final_price',
        'checked_in_at',
        'started_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
        'duration_minutes',
        'petugas_notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'scheduled_time' => 'datetime:H:i',
            'estimated_price' => 'decimal:2',
            'final_price' => 'decimal:2',
            'checked_in_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = self::generateBookingNumber();
            }
        });
    }

    public static function generateBookingNumber(): string
    {
        $date = now()->format('Ymd');
        $random = str_pad(random_int(0, 999999999), 9, '0', STR_PAD_LEFT);
        return "RS-{$date}{$random}";
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'booking_number';
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

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function addons()
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function packagePurchase()
    {
        return $this->belongsTo(PackagePurchase::class, 'package_purchase_id');
    }

    // Status helpers

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isPendingPayment()
    {
        return $this->status === 'pending_payment';
    }

    /**
     * Check if a petugas is available at given date/time
     * Considers 3-hour therapy duration + 5-minute buffer
     * 
     * @param int $petugasId
     * @param string $date (Y-m-d format)
     * @param string $time (H:i:s format)
     * @param int|null $excludeBookingId (for updates)
     * @return bool
     */
    public static function isPetugasAvailable($petugasId, $date, $time, $excludeBookingId = null)
    {
        $therapyDuration = 3; // hours
        $bufferMinutes = 5;
        
        // Calculate start and end time for new booking
        $newStart = \Carbon\Carbon::parse("$date $time");
        $newEnd = $newStart->copy()->addHours($therapyDuration)->addMinutes($bufferMinutes);
        
        // Check for conflicts with existing bookings
        $conflicts = self::where('petugas_id', $petugasId)
            ->where('scheduled_date', $date)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->when($excludeBookingId, function($q) use ($excludeBookingId) {
                $q->where('id', '!=', $excludeBookingId);
            })
            ->get()
            ->filter(function($booking) use ($newStart, $newEnd, $therapyDuration, $bufferMinutes) {
                $existingStart = \Carbon\Carbon::parse($booking->scheduled_date->format('Y-m-d') . ' ' . $booking->scheduled_time->format('H:i:s'));
                $existingEnd = $existingStart->copy()->addHours($therapyDuration)->addMinutes($bufferMinutes);
                
                // Check if times overlap
                return ($newStart < $existingEnd && $newEnd > $existingStart);
            });
        
        return $conflicts->isEmpty();
    }

    /**
     * Get available petugas for given date/time
     * 
     * @param string $date
     * @param string $time
     * @param int|null $excludeBookingId
     * @return \Illuminate\Support\Collection
     */
    public static function getAvailablePetugas($date, $time, $excludeBookingId = null)
    {
        $allPetugas = User::where('role', 'petugas')->get();
        
        return $allPetugas->filter(function($petugas) use ($date, $time, $excludeBookingId) {
            return self::isPetugasAvailable($petugas->id, $date, $time, $excludeBookingId);
        });
    }
}
