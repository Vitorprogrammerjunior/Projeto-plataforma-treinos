<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/**
 * Service para integração com gateway de pagamento.
 * Implementação atual: Stripe (pode ser adaptado para Mercado Pago).
 * 
 * NOTA: Em produção, você deve configurar as chaves no .env:
 * - STRIPE_KEY
 * - STRIPE_SECRET
 * - STRIPE_WEBHOOK_SECRET
 */
class PaymentService
{
    private SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Cria sessão de checkout no Stripe.
     * Retorna URL de redirect para o usuário completar pagamento.
     */
    public function createCheckoutSession(User $user, Plan $plan): array
    {
        // Cria assinatura pendente no banco
        $subscription = $this->subscriptionService->createPending($user, $plan);

        // Em produção, descomentar e configurar Stripe:
        /*
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card', 'boleto', 'pix'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [
                        'name' => $plan->name,
                        'description' => $plan->description,
                    ],
                    'unit_amount' => (int) ($plan->price * 100), // Stripe usa centavos
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['subscription' => $subscription->id]),
            'cancel_url' => route('payment.cancel', ['subscription' => $subscription->id]),
            'metadata' => [
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
            ],
            'customer_email' => $user->email,
        ]);

        return [
            'success' => true,
            'checkout_url' => $session->url,
            'session_id' => $session->id,
            'subscription_id' => $subscription->id,
        ];
        */

        // MODO DEMO: Simula checkout bem-sucedido para testes
        Log::info('MODO DEMO: Checkout simulado', [
            'subscription_id' => $subscription->id,
            'user' => $user->email,
            'plan' => $plan->name,
        ]);

        return [
            'success' => true,
            'checkout_url' => route('payment.demo', ['subscription' => $subscription->id]),
            'subscription_id' => $subscription->id,
            'demo_mode' => true,
        ];
    }

    /**
     * Processa webhook do Stripe (confirmação de pagamento).
     * Em produção, verificar assinatura do webhook para segurança.
     */
    public function handleWebhook(array $payload): bool
    {
        /*
        // Verificação de assinatura do webhook (OBRIGATÓRIO em produção)
        $sig = request()->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                request()->getContent(),
                $sig,
                $webhookSecret
            );
        } catch (\Exception $e) {
            Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
            return false;
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $subscriptionId = $session->metadata->subscription_id;
            $subscription = Subscription::find($subscriptionId);

            if ($subscription) {
                $this->subscriptionService->activate($subscription, $session->payment_intent);
                return true;
            }
        }
        */

        return true;
    }

    /**
     * MODO DEMO: Ativa assinatura diretamente (para testes sem gateway real).
     */
    public function activateDemo(Subscription $subscription): bool
    {
        $fakePaymentId = 'demo_' . uniqid();
        return $this->subscriptionService->activate($subscription, $fakePaymentId);
    }

    /**
     * Verifica status de um pagamento no gateway.
     */
    public function checkPaymentStatus(string $paymentId): string
    {
        // Em produção, consultar API do Stripe
        /*
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $payment = \Stripe\PaymentIntent::retrieve($paymentId);
        return $payment->status; // 'succeeded', 'pending', 'failed'
        */

        return 'succeeded'; // Demo
    }
}
