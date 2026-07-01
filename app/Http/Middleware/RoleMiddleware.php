<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: Route::middleware('role:admin') or Route::middleware('role:instructor,admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please log in to access this area.');
        }

        if (!$request->user()->hasRole($roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. Insufficient permissions.'], 403);
            }
            abort(403, 'You do not have permission to access this area.');
        }

        if (!$request->user()->isActive()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been suspended. Please contact support.');
        }

        return $next($request);
    }
}
