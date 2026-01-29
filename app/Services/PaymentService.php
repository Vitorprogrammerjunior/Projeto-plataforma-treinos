<?php

namespace App\Services;

use App\Models\PendingRegistration;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service para integração com Mercado Pago.
 * Suporta: Cartão de Crédito, PIX, Boleto
 * 
 * Documentação: https://www.mercadopago.com.br/developers/pt/docs
 * 
 * Configure no .env:
 * - MERCADO_PAGO_ACCESS_TOKEN (obrigatório)
 * - MERCADO_PAGO_PUBLIC_KEY (para frontend)
 * - MERCADO_PAGO_WEBHOOK_SECRET (opcional, para validar webhooks)
 */
class PaymentService
{
    private SubscriptionService $subscriptionService;
    private string $apiUrl = 'https://api.mercadopago.com';

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Retorna headers para API do Mercado Pago.
     */
    private function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . config('services.mercadopago.access_token'),
            'Content-Type' => 'application/json',
            'X-Idempotency-Key' => uniqid('mp_', true),
        ];
    }

    /**
     * Cria uma Preference (sessão de checkout) no Mercado Pago.
     * Redireciona usuário para página de pagamento do MP.
     */
    public function createCheckoutSession(User $user, Plan $plan): array
    {
        // Cria assinatura pendente no banco
        $subscription = $this->subscriptionService->createPending($user, $plan);

        // Verifica se tem token configurado
        $accessToken = config('services.mercadopago.access_token');
        
        if (empty($accessToken)) {
            // MODO DEMO: Sem credenciais, usa simulação
            Log::info('MODO DEMO: Checkout simulado (sem credenciais MP)', [
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

        try {
            // Monta dados da Preference
            $preferenceData = [
                'items' => [
                    [
                        'id' => (string) $plan->id,
                        'title' => $plan->name,
                        'description' => $plan->description ?? 'Acesso à plataforma de treinos',
                        'category_id' => 'services',
                        'quantity' => 1,
                        'currency_id' => 'BRL',
                        'unit_price' => (float) $plan->price,
                    ],
                ],
                'payer' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'back_urls' => [
                    'success' => route('payment.success', ['subscription' => $subscription->id]),
                    'failure' => route('payment.cancel', ['subscription' => $subscription->id]),
                    'pending' => route('payment.success', ['subscription' => $subscription->id]),
                ],
                'external_reference' => (string) $subscription->id,
                'statement_descriptor' => 'ADRI TREINOS',
                'payment_methods' => [
                    'excluded_payment_types' => [],
                    'installments' => 12,
                    'default_installments' => 1,
                ],
            ];

            // Chama API do Mercado Pago
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->apiUrl}/checkout/preferences", $preferenceData);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Mercado Pago: Preference criada', [
                    'subscription_id' => $subscription->id,
                    'preference_id' => $data['id'],
                ]);

                // Usa sandbox ou produção dependendo do ambiente
                $checkoutUrl = app()->environment('production') 
                    ? $data['init_point'] 
                    : $data['sandbox_init_point'];

                return [
                    'success' => true,
                    'checkout_url' => $checkoutUrl,
                    'preference_id' => $data['id'],
                    'subscription_id' => $subscription->id,
                    'demo_mode' => false,
                ];
            }

            Log::error('Mercado Pago: Erro ao criar preference', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Erro ao processar pagamento. Tente novamente.',
            ];

        } catch (\Exception $e) {
            Log::error('Mercado Pago: Exception', [
                'message' => $e->getMessage(),
                'subscription_id' => $subscription->id,
            ]);

            return [
                'success' => false,
                'error' => 'Erro de conexão com gateway de pagamento.',
            ];
        }
    }

    /**
     * Cria uma Preference de pagamento para registro (usuário ainda não existe).
     * Usado no fluxo: paga primeiro, depois cria a conta.
     */
    public function createPaymentForRegistration(PendingRegistration $pendingRegistration, Plan $plan): array
    {
        // Verifica se tem token configurado
        $accessToken = config('services.mercadopago.access_token');
        
        if (empty($accessToken)) {
            // MODO DEMO: Sem credenciais, usa simulação
            Log::info('MODO DEMO: Registro checkout simulado (sem credenciais MP)', [
                'pending_id' => $pendingRegistration->id,
                'email' => $pendingRegistration->email,
                'plan' => $plan->name,
            ]);

            return [
                'success' => true,
                'checkout_url' => route('payment.register.demo', ['pendingRegistration' => $pendingRegistration->id]),
                'pending_registration_id' => $pendingRegistration->id,
                'demo_mode' => true,
            ];
        }

        try {
            // Monta dados da Preference
            $preferenceData = [
                'items' => [
                    [
                        'id' => (string) $plan->id,
                        'title' => $plan->name,
                        'description' => $plan->description ?? 'Acesso à plataforma de treinos',
                        'category_id' => 'services',
                        'quantity' => 1,
                        'currency_id' => 'BRL',
                        'unit_price' => (float) $plan->price,
                    ],
                ],
                'payer' => [
                    'name' => $pendingRegistration->name,
                    'email' => $pendingRegistration->email,
                ],
                'back_urls' => [
                    'success' => route('payment.register.success'),
                    'failure' => route('payment.register.failure'),
                    'pending' => route('payment.register.pending'),
                ],
                // Usa REG_ prefix para identificar que é um registro
                'external_reference' => 'REG_' . $pendingRegistration->id,
                'statement_descriptor' => 'ADRI TREINOS',
                'payment_methods' => [
                    'excluded_payment_types' => [],
                    'installments' => 12,
                    'default_installments' => 1,
                ],
            ];

            // Chama API do Mercado Pago
            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->apiUrl}/checkout/preferences", $preferenceData);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Mercado Pago: Registration Preference criada', [
                    'pending_id' => $pendingRegistration->id,
                    'preference_id' => $data['id'],
                ]);

                // Salva preference_id no pending registration
                $pendingRegistration->update(['preference_id' => $data['id']]);

                // Usa sandbox ou produção dependendo do ambiente
                $checkoutUrl = app()->environment('production') 
                    ? $data['init_point'] 
                    : $data['sandbox_init_point'];

                return [
                    'success' => true,
                    'checkout_url' => $checkoutUrl,
                    'preference_id' => $data['id'],
                    'pending_registration_id' => $pendingRegistration->id,
                    'demo_mode' => false,
                ];
            }

            Log::error('Mercado Pago: Erro ao criar registration preference', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Erro ao processar pagamento. Tente novamente.',
            ];

        } catch (\Exception $e) {
            Log::error('Mercado Pago: Registration Exception', [
                'message' => $e->getMessage(),
                'pending_id' => $pendingRegistration->id,
            ]);

            return [
                'success' => false,
                'error' => 'Erro de conexão com gateway de pagamento.',
            ];
        }
    }

    /**
     * Cria pagamento PIX direto (sem redirect).
     * Retorna QR Code para pagamento imediato.
     */
    public function createPixPayment(User $user, Plan $plan): array
    {
        $subscription = $this->subscriptionService->createPending($user, $plan);

        $accessToken = config('services.mercadopago.access_token');
        
        if (empty($accessToken)) {
            return [
                'success' => false,
                'error' => 'Gateway de pagamento não configurado.',
            ];
        }

        try {
            $paymentData = [
                'transaction_amount' => (float) $plan->price,
                'description' => $plan->name,
                'payment_method_id' => 'pix',
                'payer' => [
                    'email' => $user->email,
                    'first_name' => explode(' ', $user->name)[0],
                    'last_name' => explode(' ', $user->name, 2)[1] ?? '',
                ],
                'external_reference' => (string) $subscription->id,
                'notification_url' => route('payment.webhook'),
            ];

            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->apiUrl}/v1/payments", $paymentData);

            if ($response->successful()) {
                $data = $response->json();
                
                // Atualiza subscription com ID do pagamento
                $subscription->update(['payment_id' => $data['id']]);

                Log::info('Mercado Pago: PIX criado', [
                    'subscription_id' => $subscription->id,
                    'payment_id' => $data['id'],
                ]);

                return [
                    'success' => true,
                    'subscription_id' => $subscription->id,
                    'payment_id' => $data['id'],
                    'qr_code' => $data['point_of_interaction']['transaction_data']['qr_code'] ?? null,
                    'qr_code_base64' => $data['point_of_interaction']['transaction_data']['qr_code_base64'] ?? null,
                    'ticket_url' => $data['point_of_interaction']['transaction_data']['ticket_url'] ?? null,
                    'expiration_date' => $data['date_of_expiration'] ?? null,
                ];
            }

            Log::error('Mercado Pago: Erro ao criar PIX', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Erro ao gerar PIX. Tente novamente.',
            ];

        } catch (\Exception $e) {
            Log::error('Mercado Pago: PIX Exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Erro de conexão.',
            ];
        }
    }

    /**
     * Processa webhook/IPN do Mercado Pago.
     * Chamado automaticamente quando pagamento é confirmado.
     */
    public function handleWebhook(array $payload): bool
    {
        Log::info('Mercado Pago: Webhook recebido', ['payload' => $payload]);

        // Tipos de notificação
        $type = $payload['type'] ?? $payload['topic'] ?? null;
        $dataId = $payload['data']['id'] ?? $payload['id'] ?? null;

        if (!$type || !$dataId) {
            Log::warning('Webhook inválido: faltando type ou data.id');
            return false;
        }

        // Processa apenas notificações de pagamento
        if ($type === 'payment') {
            return $this->processPaymentNotification($dataId);
        }

        // IPN antigo (topic=payment)
        if ($type === 'merchant_order') {
            return $this->processMerchantOrder($dataId);
        }

        return true;
    }

    /**
     * Busca detalhes do pagamento e ativa assinatura se aprovado.
     */
    private function processPaymentNotification(string $paymentId): bool
    {
        $accessToken = config('services.mercadopago.access_token');
        
        if (empty($accessToken)) {
            return false;
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiUrl}/v1/payments/{$paymentId}");

            if (!$response->successful()) {
                Log::error('Não foi possível buscar pagamento', ['payment_id' => $paymentId]);
                return false;
            }

            $payment = $response->json();
            $externalReference = $payment['external_reference'] ?? null;
            $status = $payment['status'] ?? null;

            if (!$externalReference) {
                Log::warning('Pagamento sem external_reference', ['payment_id' => $paymentId]);
                return false;
            }

            // Verifica se é um pagamento de registro (REG_) ou de assinatura
            if (str_starts_with($externalReference, 'REG_')) {
                return $this->processRegistrationPayment($externalReference, $paymentId, $status);
            }

            // Pagamento de assinatura existente
            $subscription = Subscription::find($externalReference);
            
            if (!$subscription) {
                Log::warning('Subscription não encontrada', ['subscription_id' => $externalReference]);
                return false;
            }

            // Status do Mercado Pago: approved, pending, rejected, cancelled, refunded
            switch ($status) {
                case 'approved':
                    $this->subscriptionService->activate($subscription, $paymentId);
                    Log::info('Pagamento aprovado, assinatura ativada', [
                        'subscription_id' => $externalReference,
                        'payment_id' => $paymentId,
                    ]);
                    break;

                case 'rejected':
                case 'cancelled':
                    $subscription->update(['payment_status' => 'failed']);
                    Log::info('Pagamento rejeitado/cancelado', [
                        'subscription_id' => $externalReference,
                        'status' => $status,
                    ]);
                    break;

                case 'refunded':
                    $subscription->update(['payment_status' => 'refunded']);
                    Log::info('Pagamento reembolsado', ['subscription_id' => $externalReference]);
                    break;

                default:
                    Log::info('Status pendente', ['subscription_id' => $externalReference, 'status' => $status]);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Erro processando notificação', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Processa pagamento de registro (cria usuário quando aprovado).
     */
    private function processRegistrationPayment(string $externalReference, string $paymentId, string $status): bool
    {
        $pendingId = str_replace('REG_', '', $externalReference);
        $pendingRegistration = PendingRegistration::find($pendingId);

        if (!$pendingRegistration) {
            Log::warning('PendingRegistration não encontrado', ['pending_id' => $pendingId]);
            return false;
        }

        // Atualiza payment_id
        $pendingRegistration->update(['payment_id' => $paymentId]);

        switch ($status) {
            case 'approved':
                // Se ainda não foi processado, cria o usuário
                if ($pendingRegistration->status === 'pending') {
                    $this->createUserFromPendingRegistration($pendingRegistration);
                }
                break;

            case 'rejected':
            case 'cancelled':
                $pendingRegistration->update(['status' => 'failed']);
                Log::info('Pagamento de registro rejeitado/cancelado', [
                    'pending_id' => $pendingId,
                    'status' => $status,
                ]);
                break;
        }

        return true;
    }

    /**
     * Cria usuário a partir de um PendingRegistration (chamado pelo webhook).
     */
    private function createUserFromPendingRegistration(PendingRegistration $pendingRegistration): ?User
    {
        // Verifica se já existe um usuário com este e-mail
        if (User::where('email', $pendingRegistration->email)->exists()) {
            $pendingRegistration->update(['status' => 'completed']);
            return User::where('email', $pendingRegistration->email)->first();
        }

        try {
            // Cria o usuário
            $user = User::create([
                'name' => $pendingRegistration->name,
                'email' => $pendingRegistration->email,
                'phone' => $pendingRegistration->phone,
                'password' => $pendingRegistration->password, // Já está hasheada
            ]);

            // Cria a assinatura ativa
            $plan = $pendingRegistration->plan;
            
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'payment_status' => 'approved',
                'payment_id' => $pendingRegistration->payment_id,
                'starts_at' => now(),
                'ends_at' => $plan->isMonthly() ? now()->addMonth() : null,
            ]);

            // Marca o pending registration como completed
            $pendingRegistration->update(['status' => 'completed']);

            Log::info('Usuário criado via webhook após pagamento', [
                'user_id' => $user->id,
                'pending_id' => $pendingRegistration->id,
            ]);

            // TODO: Enviar e-mail de boas vindas com credenciais

            return $user;
        } catch (\Exception $e) {
            Log::error('Erro ao criar usuário do webhook', [
                'pending_id' => $pendingRegistration->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Processa merchant_order (usado em alguns casos de IPN).
     */
    private function processMerchantOrder(string $orderId): bool
    {
        $accessToken = config('services.mercadopago.access_token');
        
        if (empty($accessToken)) {
            return false;
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiUrl}/merchant_orders/{$orderId}");

            if (!$response->successful()) {
                return false;
            }

            $order = $response->json();
            
            // Processa cada pagamento da order
            foreach ($order['payments'] ?? [] as $payment) {
                if ($payment['status'] === 'approved') {
                    $this->processPaymentNotification($payment['id']);
                }
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Erro processando merchant_order', ['error' => $e->getMessage()]);
            return false;
        }
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
     * Verifica status de um pagamento no Mercado Pago.
     */
    public function checkPaymentStatus(string $paymentId): string
    {
        $accessToken = config('services.mercadopago.access_token');
        
        if (empty($accessToken) || str_starts_with($paymentId, 'demo_')) {
            return 'approved'; // Demo mode
        }

        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiUrl}/v1/payments/{$paymentId}");

            if ($response->successful()) {
                return $response->json()['status'] ?? 'unknown';
            }
        } catch (\Exception $e) {
            Log::error('Erro verificando status', ['error' => $e->getMessage()]);
        }

        return 'unknown';
    }

    /**
     * Retorna public key para uso no frontend (Checkout Bricks).
     */
    public function getPublicKey(): ?string
    {
        return config('services.mercadopago.public_key');
    }
}
