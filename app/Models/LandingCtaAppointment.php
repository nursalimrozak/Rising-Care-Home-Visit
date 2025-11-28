<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingCtaAppointment extends Model
{
    use HasFactory;

    protected $table = 'landing_cta_appointment';

    protected $fillable = [
        'section_title',
        'section_subtitle',
        'background_image',
        'background_color',
        'button_text',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
