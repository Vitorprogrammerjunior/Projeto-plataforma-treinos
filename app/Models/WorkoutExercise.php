<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_id',
        'name',
        'sets',
        'reps',
        'rest',
        'weight',
        'notes',
        'video_url',
        'image',
        'order',
    ];

    public function workout(): BelongsTo
    {
        return $this->belongsTo(Workout::class);
    }

    public function getFormattedSetsRepsAttribute(): string
    {
        $parts = [];
        if ($this->sets) {
            $parts[] = "{$this->sets} séries";
        }
        if ($this->reps) {
            $parts[] = "{$this->reps} reps";
        }
        return implode(' x ', $parts) ?: '-';
    }
}
