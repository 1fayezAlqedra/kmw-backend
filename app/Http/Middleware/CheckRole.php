<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Usage:
     * ->middleware('role:admin')
     * ->middleware('role:admin,editor')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // 1. Unauthenticated
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // 2. Super Admin override (Full access)
        if (isset($user->is_super_admin) && $user->is_super_admin) {
            return $next($request);
        }

        // 3. If no roles specified → block access
        if (empty($roles)) {
            return response()->json([
                'success' => false,
                'message' => 'No role assigned to route.'
            ], 403);
        }

        // 4. Check if user has one of allowed roles
        if (!in_array($user->role, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        return $next($request);
    }
}
