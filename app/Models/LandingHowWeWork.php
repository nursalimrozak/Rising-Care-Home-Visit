<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingHowWeWork extends Model
{
    use HasFactory;

    protected $table = 'landing_how_we_work';

    protected $fillable = [
        'step_number',
        'title',
        'description',
        'icon',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'step_number' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
