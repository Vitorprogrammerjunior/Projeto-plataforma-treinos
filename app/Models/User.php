<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User - Usuário da plataforma.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Todas as assinaturas do usuário.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Assinatura ativa atual.
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
                    ->where('payment_status', 'approved')
                    ->where('expires_at', '>', now())
                    ->latest('expires_at');
    }

    /**
     * Verifica se usuário tem acesso ativo aos vídeos.
     */
    public function hasActiveAccess(): bool
    {
        return $this->activeSubscription()->exists();
    }

    /**
     * Verifica se é administrador.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Retorna dias restantes de acesso.
     */
    public function getDaysRemainingAttribute(): int
    {
        $subscription = $this->activeSubscription;
        return $subscription ? $subscription->days_remaining : 0;
    }

    /**
     * Retorna data de expiração formatada.
     */
    public function getAccessExpiresAtAttribute(): ?string
    {
        $subscription = $this->activeSubscription;
        return $subscription?->expires_at?->format('d/m/Y');
    }

    /**
     * Treinos do usuário.
     */
    public function workouts(): HasMany
    {
        return $this->hasMany(Workout::class)->orderBy('order');
    }

    /**
     * Treinos ativos do usuário.
     */
    public function activeWorkouts(): HasMany
    {
        return $this->hasMany(Workout::class)->where('is_active', true)->orderBy('order');
    }

    /**
     * Planos alimentares do usuário.
     */
    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlan::class);
    }

    /**
     * Plano alimentar ativo do usuário.
     */
    public function activeMealPlan(): HasOne
    {
        return $this->hasOne(MealPlan::class)->where('is_active', true)->latest();
    }

    /**
     * Vídeos personalizados do usuário.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
