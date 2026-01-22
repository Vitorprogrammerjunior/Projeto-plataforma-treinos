<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Service responsável por gerenciar assinaturas.
 * Centraliza toda lógica de criação/ativação de acesso.
 */
class SubscriptionService
{
    /**
     * Cria uma nova assinatura pendente (aguardando pagamento).
     */
    public function createPending(User $user, Plan $plan): Subscription
    {
        return Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'payment_status' => 'pending',
            'amount_paid' => $plan->price,
        ]);
    }

    /**
     * Ativa uma assinatura após confirmação de pagamento.
     * Esta é a função chamada pelo webhook ou após aprovação manual.
     */
    public function activate(Subscription $subscription, string $paymentId): bool
    {
        try {
            $plan = $subscription->plan;

            $subscription->update([
                'payment_id' => $paymentId,
                'payment_status' => 'approved',
                'starts_at' => now(),
                'expires_at' => now()->addDays($plan->duration_days),
            ]);

            Log::info('Assinatura ativada', [
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'plan_id' => $subscription->plan_id,
                'expires_at' => $subscription->expires_at,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erro ao ativar assinatura', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Marca assinatura como falha (pagamento recusado).
     */
    public function fail(Subscription $subscription, string $reason = ''): void
    {
        $subscription->update([
            'payment_status' => 'failed',
        ]);

        Log::warning('Pagamento falhou', [
            'subscription_id' => $subscription->id,
            'reason' => $reason,
        ]);
    }

    /**
     * Renova assinatura existente (estende a data de expiração).
     */
    public function renew(User $user, Plan $plan, string $paymentId): Subscription
    {
        $activeSubscription = $user->activeSubscription;

        // Se tem assinatura ativa, estende a partir da data atual de expiração
        $startsAt = $activeSubscription && $activeSubscription->expires_at->isFuture()
            ? $activeSubscription->expires_at
            : now();

        return Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'payment_id' => $paymentId,
            'payment_status' => 'approved',
            'amount_paid' => $plan->price,
            'starts_at' => $startsAt,
            'expires_at' => $startsAt->copy()->addDays($plan->duration_days),
        ]);
    }

    /**
     * Verifica se usuário pode acessar conteúdo premium.
     */
    public function canAccessPremiumContent(User $user): bool
    {
        return $user->hasActiveAccess();
    }

    /**
     * Retorna histórico de assinaturas do usuário.
     */
    public function getHistory(User $user)
    {
        return $user->subscriptions()
                    ->with('plan')
                    ->latest()
                    ->get();
    }
}
