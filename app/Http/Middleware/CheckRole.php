<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact TEVDA administration.');
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (!$user->role || !in_array($user->role->slug, $roles)) {
            abort(403, 'Unauthorized access. You do not have the required role to view this section.');
        }

        return $next($request);
    }
}
