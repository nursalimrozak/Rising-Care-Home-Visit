<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthQuestionCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(HealthScreeningQuestion::class, 'category_id')->orderBy('order');
    }
}
