<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Video;

/**
 * Controller da Landing Page.
 * Página pública de alta conversão.
 */
class HomeController extends Controller
{
    public function index()
    {
        // Planos ativos para exibição
        $plans = Plan::active()->orderBy('price')->get();

        // Vídeos gratuitos como preview
        $freeVideos = Video::active()->free()->ordered()->take(3)->get();

        // Estatísticas para social proof
        $stats = [
            'videos' => Video::active()->count(),
            'students' => \App\Models\User::where('is_admin', false)->count(),
        ];

        return view('home', compact('plans', 'freeVideos', 'stats'));
    }
}
