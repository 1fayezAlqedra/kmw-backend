<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CreateAdminRequest; // استدعاء الـ Request
use App\Services\V1\AuthService;                 // استدعاء الـ Service
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // تسجيل الدخول
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials.'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'user' => $user
        ]);
    }

    // إنشاء أدمن جديد (للـ SEO فقط)
    public function createAdmin(CreateAdminRequest $request): JsonResponse
    {
        $admin = $this->authService->createAdmin($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully.',
            'data' => $admin
        ], 201);
    }

    // تسجيل الخروج
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'message' => 'Logged out.']);
    }

    // بيانات المستخدم الحالي
    public function me(Request $request): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $request->user()]);
    }
}
