<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleOrPermission
{
    public function handle(Request $request, Closure $next, ...$abilities): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        foreach ($abilities as $ability) {
            if (str_starts_with($ability, 'role:') && $user->hasRole(substr($ability, 5))) {
                return $next($request);
            }

            if (str_starts_with($ability, 'permission:') && $user->hasPermission(substr($ability, 11))) {
                return $next($request);
            }
        }

        return response()->view('errors.403', [], 403);
    }
}
