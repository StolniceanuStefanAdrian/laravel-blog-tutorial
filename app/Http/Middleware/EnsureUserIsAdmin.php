<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifică dacă utilizatorul este autentificat ȘI dacă atributul 'is_admin' este true
        if (!auth()->check() || !auth()->user()->is_admin) {
            // Dacă nu este autentificat sau nu este admin, aruncă o eroare 403 (Forbidden)
            abort(403, 'Access denied. Admin privileges required.');
        }

        // Dacă utilizatorul este admin, continuă cu cererea
        return $next($request);
    }
}