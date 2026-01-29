<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model para registros pendentes de pagamento.
 * Armazena dados do usuário até que o pagamento seja confirmado.
 */
class PendingRegistration extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'plan_id',
        'payment_id',
        'preference_id',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Plano escolhido.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Verifica se o registro ainda está válido (não expirou).
     */
    public function isValid(): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Scope para registros pendentes válidos.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope para buscar por email.
     */
    public function scopeByEmail($query, string $email)
    {
        return $query->where('email', $email);
    }
}
