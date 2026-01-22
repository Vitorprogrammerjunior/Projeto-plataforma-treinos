<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\PaymentService;
use Illuminate\Http\Request;

/**
 * Controller para gestão de planos e processo de compra.
 */
class PlanController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    /**
     * Lista planos disponíveis para compra.
     */
    public function index()
    {
        $plans = Plan::active()->orderBy('price')->get();
        
        return view('plans.index', compact('plans'));
    }

    /**
     * Exibe detalhes de um plano.
     */
    public function show(Plan $plan)
    {
        if (!$plan->is_active) {
            abort(404);
        }

        return view('plans.show', compact('plan'));
    }

    /**
     * Inicia processo de checkout.
     */
    public function checkout(Request $request, Plan $plan)
    {
        if (!$plan->is_active) {
            return back()->with('error', 'Este plano não está mais disponível.');
        }

        $user = $request->user();

        // Verifica se usuário já tem acesso ativo
        if ($user->hasActiveAccess()) {
            return redirect()
                ->route('dashboard')
                ->with('info', 'Você já possui uma assinatura ativa!');
        }

        // Cria sessão de pagamento
        $result = $this->paymentService->createCheckoutSession($user, $plan);

        if ($result['success']) {
            return redirect($result['checkout_url']);
        }

        return back()->with('error', 'Erro ao processar pagamento. Tente novamente.');
    }
}
