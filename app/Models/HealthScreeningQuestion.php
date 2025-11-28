<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthScreeningQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'question', 'type', 'is_active', 'order', 'options'];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(HealthQuestionCategory::class, 'category_id');
    }

    public function responses()
    {
        return $this->hasMany(UserHealthResponse::class, 'question_id');
    }
}
