<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Lista todos os clientes (não admin).
     */
    public function index()
    {
        $clients = User::where('is_admin', false)
            ->with(['activeSubscription', 'activeWorkouts', 'activeMealPlan'])
            ->latest()
            ->paginate(20);

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Mostra detalhes de um cliente específico.
     */
    public function show(User $client)
    {
        $client->load([
            'subscriptions' => fn($q) => $q->latest(),
            'workouts' => fn($q) => $q->with('exercises')->orderBy('order'),
            'mealPlans' => fn($q) => $q->with(['meals.items'])->latest(),
            'videos' => fn($q) => $q->latest(),
        ]);

        return view('admin.clients.show', compact('client'));
    }

    /**
     * Página para gerenciar treinos do cliente.
     */
    public function workouts(User $client)
    {
        $client->load(['workouts' => fn($q) => $q->with('exercises')->orderBy('order')]);
        
        return view('admin.clients.workouts', compact('client'));
    }

    /**
     * Página para gerenciar dieta do cliente.
     */
    public function mealPlans(User $client)
    {
        $client->load(['mealPlans' => fn($q) => $q->with(['meals.items'])->latest()]);
        
        return view('admin.clients.meal-plans', compact('client'));
    }

    /**
     * Página para gerenciar vídeos do cliente.
     */
    public function videos(User $client)
    {
        $client->load(['videos' => fn($q) => $q->latest()]);
        
        return view('admin.clients.videos', compact('client'));
    }
}
