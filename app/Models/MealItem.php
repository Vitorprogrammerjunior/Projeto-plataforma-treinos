<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_id',
        'name',
        'quantity',
        'calories',
        'protein',
        'carbs',
        'fat',
        'image',
        'notes',
        'alternatives',
        'order',
    ];

    public function meal(): BelongsTo
    {
        return $this->belongsTo(Meal::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        
        return asset('storage/' . $this->image);
    }

    public function getMacrosTextAttribute(): string
    {
        $parts = [];
        if ($this->protein) $parts[] = "P: {$this->protein}g";
        if ($this->carbs) $parts[] = "C: {$this->carbs}g";
        if ($this->fat) $parts[] = "G: {$this->fat}g";
        return implode(' | ', $parts) ?: '-';
    }
}
