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
     * ->middleware('role:super_admin')
     * ->middleware('role:admin,super_admin')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'super_admin') {
            return $next($request);
        }

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized by CheckRole'
        ], 403);
    }
}

