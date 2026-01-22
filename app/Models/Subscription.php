<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Subscription - Representa assinatura de um usuário.
 */
class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'payment_id',
        'payment_status',
        'amount_paid',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Usuário dono da assinatura.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Plano da assinatura.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Verifica se a assinatura está ativa.
     */
    public function isActive(): bool
    {
        return $this->payment_status === 'approved' 
            && $this->expires_at 
            && $this->expires_at->isFuture();
    }

    /**
     * Scope para assinaturas ativas.
     */
    public function scopeActive($query)
    {
        return $query->where('payment_status', 'approved')
                     ->where('expires_at', '>', now());
    }

    /**
     * Scope para assinaturas aprovadas.
     */
    public function scopeApproved($query)
    {
        return $query->where('payment_status', 'approved');
    }

    /**
     * Retorna dias restantes da assinatura.
     */
    public function getDaysRemainingAttribute(): int
    {
        if (!$this->expires_at || $this->expires_at->isPast()) {
            return 0;
        }
        return (int) now()->diffInDays($this->expires_at, false);
    }
}
