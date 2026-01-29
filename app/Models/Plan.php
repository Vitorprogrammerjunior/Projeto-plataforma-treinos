<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Plan - Representa os planos de assinatura.
 * 
 * Tipos de plano:
 * - 'single': Pagamento único (acesso por duration_days)
 * - 'monthly': Assinatura mensal recorrente
 */
class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'type',
        'duration_days',
        'features',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Assinaturas deste plano.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Scope para planos ativos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Verifica se é plano de pagamento único.
     */
    public function isSinglePayment(): bool
    {
        return $this->type === 'single';
    }

    /**
     * Verifica se é assinatura mensal.
     */
    public function isMonthly(): bool
    {
        return $this->type === 'monthly';
    }

    /**
     * Retorna label do tipo de plano.
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'single' => 'Pagamento Único',
            'monthly' => 'Mensal',
            default => 'Pagamento Único',
        };
    }

    /**
     * Retorna preço formatado em BRL.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }
}
