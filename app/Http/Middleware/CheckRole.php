<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * التحقق من صلاحيات المستخدم
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // 1. التأكد أن المستخدم مسجل دخول
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        // 2. إذا كان المستخدم SEO، فهو يملك صلاحية كل شيء (Super Admin)
        if ($user->role === 'seo') {
            return $next($request);
        }

        // 3. إذا كان المطلوب هو 'admin'، نسمح للـ admin فقط
        if ($role === 'admin' && $user->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized. Admin access required.'], 403);
        }

        // 4. إذا كان المطلوب أي دور آخر، نتحقق من تطابق الدور
        if ($user->role !== $role) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        return $next($request);
    }
}
