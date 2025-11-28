<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'password',
        'role',
        'occupation_id',
        'membership_id',
        'loyalty_points',
        'avatar',
        'is_active',
        'date_of_birth',
        'bpjs_number',
        'gender',
        'religion',
        'marital_status',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'emergency_contact_address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'loyalty_points' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->code = self::generateUniqueCode();
        });
    }

    public static function generateUniqueCode()
    {
        do {
            $segments = [];
            for ($i = 0; $i < 4; $i++) {
                $segments[] = strtoupper(Str::random(6));
            }
            $code = implode('-', $segments);
        } while (self::where('code', $code)->exists());

        return $code;
    }

    // Relationships
    public function occupation()
    {
        return $this->belongsTo(Occupation::class);
    }

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function bookingsAsCustomer()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function bookingsAsPetugas()
    {
        return $this->hasMany(Booking::class, 'petugas_id');
    }

    public function bookings()
    {
        return $this->bookingsAsCustomer();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function loyaltyTransactions()
    {
        return $this->hasMany(LoyaltyTransaction::class, 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'customer_id');
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class, 'customer_id');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function payouts()
    {
        return $this->hasMany(Payout::class);
    }

    public function payoutDetail()
    {
        return $this->hasOne(UserPayoutDetail::class);
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    // Helper methods
    public function isSuperadmin()
    {
        return $this->role === 'superadmin';
    }

    public function isAdminStaff()
    {
        return $this->role === 'admin_staff';
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    public function isCustomer()
    {
        return $this->role === 'customer';
    }

    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    public function getRouteKeyName()
    {
        return 'code';
    }
}
