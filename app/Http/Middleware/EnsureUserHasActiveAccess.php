<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que verifica se usuário tem acesso ativo (assinatura válida).
 * Usado para proteger rotas de conteúdo premium.
 */
class EnsureUserHasActiveAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->hasActiveAccess()) {
            // Se for AJAX/API, retorna JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Você precisa de uma assinatura ativa para acessar este conteúdo.',
                    'redirect' => route('plans.index'),
                ], 403);
            }

            // Redireciona para página de planos com mensagem
            return redirect()
                ->route('plans.index')
                ->with('warning', 'Você precisa de uma assinatura ativa para acessar este conteúdo.');
        }

        return $next($request);
    }
}
