<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Controleer of de ingelogde gebruiker een admin is
        if (!auth()->check() || !auth()->user()->admin) {
            // Redirect naar de homepage of een andere pagina met een foutmelding
            return redirect()->route('games.index')->with('error', 'You do not have access to this page.');
        }

        return $next($request);
    }
}
