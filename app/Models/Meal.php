<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_plan_id',
        'name',
        'time',
        'description',
        'calories',
        'order',
    ];

    protected $casts = [
        'time' => 'datetime:H:i',
    ];

    public const MEAL_TYPES = [
        'Café da Manhã',
        'Lanche da Manhã',
        'Almoço',
        'Lanche da Tarde',
        'Jantar',
        'Ceia',
    ];

    public function mealPlan(): BelongsTo
    {
        return $this->belongsTo(MealPlan::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(MealItem::class)->orderBy('order');
    }

    public function getTotalCaloriesAttribute(): int
    {
        return $this->items->sum('calories') ?? 0;
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->time ? $this->time->format('H:i') : '';
    }
}
