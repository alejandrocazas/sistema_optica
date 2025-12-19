<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Si el usuario no está logueado, fuera.
        if (!auth()->check()) {
            return redirect('login');
        }

        // Si el usuario es 'admin', tiene pase libre a todo.
        if (auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Verificamos si el rol del usuario está en la lista de roles permitidos para esta ruta
        // $roles es un array que pasaremos desde la ruta (ej: ['vendedor', 'optometrista'])
        if (in_array(auth()->user()->role, $roles)) {
            return $next($request);
        }

        // Si no tiene permiso, error 403 (Prohibido)
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}