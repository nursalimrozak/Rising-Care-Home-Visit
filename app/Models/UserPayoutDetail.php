<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayoutDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_type',
        'provider_name',
        'account_number',
        'account_holder_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
