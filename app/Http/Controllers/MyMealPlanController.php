<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyMealPlanController extends Controller
{
    /**
     * Exibe o plano alimentar do cliente logado.
     */
    public function index()
    {
        $user = Auth::user();
        
        $mealPlan = $user->mealPlans()
            ->where('is_active', true)
            ->with(['meals.items'])
            ->first();

        $allMealPlans = $user->mealPlans()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.meal-plan', compact('mealPlan', 'allMealPlans'));
    }

    /**
     * Exibe um plano alimentar específico (histórico).
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $mealPlan = $user->mealPlans()
            ->with(['meals.items'])
            ->findOrFail($id);

        return view('dashboard.meal-plan-detail', compact('mealPlan'));
    }
}
