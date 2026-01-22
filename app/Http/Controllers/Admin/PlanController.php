<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;

/**
 * Controller CRUD de Planos (Admin).
 */
class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('subscriptions')
            ->orderBy('price')
            ->paginate(15);

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(PlanRequest $request)
    {
        Plan::create($request->validated());

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plano criado com sucesso!');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(PlanRequest $request, Plan $plan)
    {
        $plan->update($request->validated());

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plano atualizado com sucesso!');
    }

    public function destroy(Plan $plan)
    {
        // Verifica se tem assinaturas ativas
        if ($plan->subscriptions()->active()->exists()) {
            return back()->with('error', 'Não é possível excluir um plano com assinaturas ativas.');
        }

        $plan->delete();

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Plano excluído com sucesso!');
    }

    /**
     * Toggle status ativo/inativo.
     */
    public function toggleActive(Plan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);

        $status = $plan->is_active ? 'ativado' : 'desativado';

        return back()->with('success', "Plano {$status} com sucesso!");
    }
}
