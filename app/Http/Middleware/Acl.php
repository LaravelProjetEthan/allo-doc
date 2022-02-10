<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * Pour utiliser ce Middleware, il faut le déclarer dans \App\Http\Kernel
 * dans la partie $routeMiddleware = []
 * Il sera alors possible de l'utiliser sur une route par ex
 * Route::get('show-api-search', [ShowController::class, 'searchApi'])->name('show-api-search')->middleware('acl');
 */
class Acl
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param ...$routeRoles
     * @return mixed|void
     */
    public function handle(Request $request, Closure $next, ...$routeRoles)
    {
        // on vérifie que l'utilisateur est connecté
        if (Auth::check()) {
            $userConnected = Auth::user();
            $userRoles = explode(",", $userConnected->role);

            // on regarde tous les droits autorisés sur la route
            foreach ($routeRoles as $roleRoute) {
                // on vérifie que le rôle en cours d'analyse de la route existe dans la liste des rôles de l'utilisateur
                if (in_array($roleRoute, $userRoles)) {
                    return $next($request);
                }
            }
        }

        abort(403);
    }
}
