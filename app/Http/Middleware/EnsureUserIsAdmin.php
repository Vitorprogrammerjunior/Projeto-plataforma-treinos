<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que verifica se usuário é administrador.
 * Usado para proteger rotas do painel admin.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Acesso não autorizado.',
                ], 403);
            }

            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
