<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Video;

/**
 * Controller do Dashboard Administrativo.
 */
class AdminDashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $stats = [
            'total_users' => User::where('is_admin', false)->count(),
            'active_subscriptions' => Subscription::active()->count(),
            'total_videos' => Video::count(),
            'total_revenue' => Subscription::approved()->sum('amount_paid'),
        ];

        // Últimas assinaturas
        $recentSubscriptions = Subscription::with(['user', 'plan'])
            ->latest()
            ->take(10)
            ->get();

        // Usuários com acesso ativo
        $activeUsers = User::whereHas('subscriptions', function ($q) {
            $q->active();
        })->count();

        return view('admin.dashboard', compact('stats', 'recentSubscriptions', 'activeUsers'));
    }
}
