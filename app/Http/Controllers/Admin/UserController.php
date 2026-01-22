<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

/**
 * Controller de Usuários (Admin).
 */
class UserController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService
    ) {}

    public function index(Request $request)
    {
        $users = User::query()
            ->where('is_admin', false)
            ->with('activeSubscription.plan')
            ->when($request->search, function ($q, $s) {
                $q->where(function ($query) use ($s) {
                    $query->where('name', 'like', "%{$s}%")
                          ->orWhere('email', 'like', "%{$s}%");
                });
            })
            ->when($request->status === 'active', function ($q) {
                $q->whereHas('subscriptions', fn($s) => $s->active());
            })
            ->when($request->status === 'inactive', function ($q) {
                $q->whereDoesntHave('subscriptions', fn($s) => $s->active());
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['subscriptions.plan']);

        $subscriptionHistory = $this->subscriptionService->getHistory($user);

        return view('admin.users.show', compact('user', 'subscriptionHistory'));
    }

    /**
     * Concede acesso manual a um usuário (útil para testes ou cortesias).
     */
    public function grantAccess(Request $request, User $user)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'days' => 'required|integer|min:1|max:365',
        ]);

        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'payment_id' => 'manual_' . uniqid(),
            'payment_status' => 'approved',
            'amount_paid' => 0,
            'starts_at' => now(),
            'expires_at' => now()->addDays($request->days),
        ]);

        return back()->with('success', "Acesso de {$request->days} dias concedido a {$user->name}.");
    }

    /**
     * Revoga acesso de um usuário.
     */
    public function revokeAccess(User $user)
    {
        $user->subscriptions()->active()->update([
            'expires_at' => now(),
        ]);

        return back()->with('success', "Acesso de {$user->name} revogado.");
    }
}
