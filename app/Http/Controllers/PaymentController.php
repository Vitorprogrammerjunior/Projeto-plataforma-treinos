<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Http\Request;
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
}
