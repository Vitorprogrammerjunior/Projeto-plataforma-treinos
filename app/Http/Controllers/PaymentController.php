<?php

namespace App\Http\Controllers;

use App\Models\PendingRegistration;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Controller para callbacks de pagamento.
 */
class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    /**
     * Página de confirmação de pagamento (DEMO).
     * Em produção, seria o callback do Stripe.
     */
    public function demo(Subscription $subscription)
    {
        // Verifica se pertence ao usuário logado
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        // Se já está aprovada, redireciona
        if ($subscription->payment_status === 'approved') {
            return redirect()
                ->route('dashboard')
                ->with('success', 'Sua assinatura já está ativa!');
        }

        return view('payment.demo', compact('subscription'));
    }

    /**
     * Confirma pagamento (DEMO).
     */
    public function confirmDemo(Subscription $subscription)
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        $success = $this->paymentService->activateDemo($subscription);

        if ($success) {
            return redirect()
                ->route('dashboard')
                ->with('success', 'Pagamento confirmado! Seu acesso foi liberado.');
        }

        return back()->with('error', 'Erro ao processar pagamento.');
    }

    /**
     * Callback de sucesso do gateway.
     */
    public function success(Request $request, Subscription $subscription)
    {
        // Verifica se pertence ao usuário logado
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        // Se ainda está pendente, aguarda webhook
        if ($subscription->payment_status === 'pending') {
            return view('payment.processing', compact('subscription'));
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pagamento confirmado! Seu acesso foi liberado.');
    }

    /**
     * Callback de cancelamento.
     */
    public function cancel(Subscription $subscription)
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        return redirect()
            ->route('plans.index')
            ->with('info', 'Pagamento cancelado. Você pode tentar novamente quando quiser.');
    }

    /**
     * Webhook do gateway de pagamento.
     * Rota pública que recebe notificações do Stripe/MP.
     */
    public function webhook(Request $request)
    {
        Log::info('Webhook recebido', $request->all());

        $result = $this->paymentService->handleWebhook($request->all());

        return response()->json(['received' => $result]);
    }

    // =====================================================
    // MÉTODOS PARA REGISTRO COM PAGAMENTO
    // =====================================================

    /**
     * Página de confirmação de pagamento do registro (DEMO).
     */
    public function registerDemo(PendingRegistration $pendingRegistration)
    {
        // Verifica se ainda é válido
        if (!$pendingRegistration->isValid()) {
            return redirect()
                ->route('register')
                ->with('error', 'Registro expirado. Por favor, tente novamente.');
        }

        // Se já foi processado, informa
        if ($pendingRegistration->status !== 'pending') {
            return redirect()
                ->route('login')
                ->with('info', 'Este registro já foi processado. Faça login para continuar.');
        }

        return view('payment.register-demo', compact('pendingRegistration'));
    }

    /**
     * Confirma pagamento do registro (DEMO).
     */
    public function confirmRegisterDemo(PendingRegistration $pendingRegistration)
    {
        // Verifica se ainda é válido
        if (!$pendingRegistration->isValid()) {
            return redirect()
                ->route('register')
                ->with('error', 'Registro expirado. Por favor, tente novamente.');
        }

        // Se já foi processado
        if ($pendingRegistration->status !== 'pending') {
            return redirect()
                ->route('login')
                ->with('info', 'Este registro já foi processado. Faça login para continuar.');
        }

        // Criar o usuário
        $user = $this->createUserFromPendingRegistration($pendingRegistration);

        if (!$user) {
            return redirect()
                ->route('register')
                ->with('error', 'Erro ao criar conta. Por favor, tente novamente.');
        }

        // Fazer login
        Auth::login($user);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pagamento confirmado! Sua conta foi criada e seu acesso foi liberado.');
    }

    /**
     * Callback de sucesso do pagamento do registro (Mercado Pago).
     */
    public function registerSuccess(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $status = $request->query('status');
        $externalReference = $request->query('external_reference');

        Log::info('Register Success Callback', [
            'payment_id' => $paymentId,
            'status' => $status,
            'external_reference' => $externalReference,
        ]);

        // Se não tem referência externa, redireciona para login
        if (!$externalReference || !str_starts_with($externalReference, 'REG_')) {
            return redirect()
                ->route('login')
                ->with('info', 'Se seu pagamento foi aprovado, sua conta será criada automaticamente.');
        }

        // Extrai o ID do pending registration
        $pendingId = str_replace('REG_', '', $externalReference);
        $pendingRegistration = PendingRegistration::find($pendingId);

        if (!$pendingRegistration) {
            return redirect()
                ->route('register')
                ->with('error', 'Registro não encontrado. Por favor, tente novamente.');
        }

        // Atualiza o payment_id
        $pendingRegistration->update(['payment_id' => $paymentId]);

        // Se o status é approved, cria o usuário imediatamente
        if ($status === 'approved') {
            $user = $this->createUserFromPendingRegistration($pendingRegistration);
            
            if ($user) {
                Auth::login($user);
                return redirect()
                    ->route('dashboard')
                    ->with('success', 'Pagamento confirmado! Sua conta foi criada e seu acesso foi liberado.');
            }
        }

        // Se está pendente (PIX, boleto), mostra página de aguardo
        return view('payment.register-processing', compact('pendingRegistration'));
    }

    /**
     * Callback de falha do pagamento do registro.
     */
    public function registerFailure(Request $request)
    {
        $externalReference = $request->query('external_reference');

        Log::info('Register Failure Callback', [
            'external_reference' => $externalReference,
            'query' => $request->query(),
        ]);

        // Marca como failed se encontrar
        if ($externalReference && str_starts_with($externalReference, 'REG_')) {
            $pendingId = str_replace('REG_', '', $externalReference);
            PendingRegistration::where('id', $pendingId)->update(['status' => 'failed']);
        }

        return redirect()
            ->route('register')
            ->with('error', 'Pagamento não aprovado. Por favor, tente novamente.');
    }

    /**
     * Callback de pagamento pendente do registro.
     */
    public function registerPending(Request $request)
    {
        $externalReference = $request->query('external_reference');

        if ($externalReference && str_starts_with($externalReference, 'REG_')) {
            $pendingId = str_replace('REG_', '', $externalReference);
            $pendingRegistration = PendingRegistration::find($pendingId);

            if ($pendingRegistration) {
                return view('payment.register-processing', compact('pendingRegistration'));
            }
        }

        return redirect()
            ->route('login')
            ->with('info', 'Pagamento em processamento. Você receberá um e-mail quando for confirmado.');
    }

    /**
     * Cria um usuário a partir de um PendingRegistration.
     */
    private function createUserFromPendingRegistration(PendingRegistration $pendingRegistration): ?User
    {
        // Verifica se já foi processado
        if ($pendingRegistration->status === 'completed') {
            // Tenta encontrar o usuário existente
            return User::where('email', $pendingRegistration->email)->first();
        }

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
            
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'payment_status' => 'approved',
                'payment_id' => $pendingRegistration->payment_id,
                'starts_at' => now(),
                'ends_at' => $plan->isMonthly() ? now()->addMonth() : null,
            ]);

            // Marca o pending registration como completed
            $pendingRegistration->update(['status' => 'completed']);

            Log::info('Usuário criado com sucesso após pagamento', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
            ]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Erro ao criar usuário do PendingRegistration', [
                'pending_id' => $pendingRegistration->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
