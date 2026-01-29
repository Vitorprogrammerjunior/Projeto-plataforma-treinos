<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PendingRegistration;
use App\Models\Plan;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    ) {}

    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Pega o plano da URL se existir
        $plan = null;
        if ($request->has('plan')) {
            $plan = Plan::where('slug', $request->plan)->active()->first();
        }

        // Busca todos os planos ativos
        $plans = Plan::active()->orderBy('price')->get();

        return view('auth.register', compact('plans', 'plan'));
    }

    /**
     * Handle an incoming registration request.
     * Cria registro pendente e redireciona para pagamento.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        // Verifica se email já existe como usuário
        if (User::where('email', $request->email)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Este email já está cadastrado. Faça login para continuar.']);
        }

        // Busca o plano
        $plan = Plan::findOrFail($request->plan_id);

        // Remove registros pendentes antigos do mesmo email
        PendingRegistration::where('email', $request->email)
            ->where('status', 'pending')
            ->delete();

        // Cria registro pendente
        $pendingRegistration = PendingRegistration::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'plan_id' => $plan->id,
            'status' => 'pending',
            'expires_at' => now()->addHours(24), // Link expira em 24h
        ]);

        // Cria sessão de pagamento usando o PaymentService
        $result = $this->paymentService->createPaymentForRegistration($pendingRegistration, $plan);

        if ($result['success']) {
            // Salva o preference_id
            $pendingRegistration->update([
                'preference_id' => $result['preference_id'] ?? null,
            ]);

            return redirect($result['checkout_url']);
        }

        // Se falhou, remove o registro pendente
        $pendingRegistration->delete();

        return back()
            ->withInput()
            ->with('error', $result['error'] ?? 'Erro ao processar pagamento. Tente novamente.');
    }
}
