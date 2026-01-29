<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model Tab - Representa as abas que organizam os vídeos.
 * O admin cria abas e coloca vídeos dentro delas.
 */
class Tab extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot do model para criar slug automaticamente.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tab) {
            if (empty($tab->slug)) {
                $tab->slug = Str::slug($tab->name);
            }
        });

        static::updating(function ($tab) {
            if ($tab->isDirty('name') && !$tab->isDirty('slug')) {
                $tab->slug = Str::slug($tab->name);
            }
        });
    }

    /**
     * Vídeos desta aba.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('order');
    }

    /**
     * Scope para abas ativas.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para ordenar por ordem definida.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    /**
     * Conta vídeos ativos nesta aba.
     */
    public function getActiveVideosCountAttribute(): int
    {
        return $this->videos()->where('is_active', true)->count();
    }
}
