<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_unless($user?->is_active, 403);

        if ($user->role !== 'admin') {
            abort_unless(in_array($user->role, $roles, true), 403);
        }

        return $next($request);
    }
}
