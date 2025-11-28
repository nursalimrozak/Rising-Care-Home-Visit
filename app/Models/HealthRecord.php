<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'category_id',
        'answers',
        'notes',
        'recorded_at',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'recorded_at' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function category()
    {
        return $this->belongsTo(HealthQuestionCategory::class, 'category_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
