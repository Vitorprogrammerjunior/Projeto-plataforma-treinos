<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyWorkoutController extends Controller
{
    /**
     * Exibe os treinos do cliente logado.
     */
    public function index()
    {
        $user = Auth::user();
        
        $workouts = $user->workouts()
            ->where('is_active', true)
            ->with('exercises')
            ->orderBy('order')
            ->get();

        return view('dashboard.workouts', compact('workouts'));
    }

    /**
     * Exibe um treino específico.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $workout = $user->workouts()
            ->with('exercises')
            ->findOrFail($id);

        return view('dashboard.workout-detail', compact('workout'));
    }
}
