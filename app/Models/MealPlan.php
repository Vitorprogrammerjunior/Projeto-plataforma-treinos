<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'objective',
        'daily_calories',
        'protein_grams',
        'carbs_grams',
        'fat_grams',
        'start_date',
        'end_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class)->orderBy('order');
    }

    public function getTotalCaloriesAttribute(): int
    {
        return $this->meals->sum(function ($meal) {
            return $meal->items->sum('calories');
        });
    }

    public function getMacrosAttribute(): array
    {
        return [
            'protein' => $this->protein_grams,
            'carbs' => $this->carbs_grams,
            'fat' => $this->fat_grams,
        ];
    }
}
