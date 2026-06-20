<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle Admin Login and Issue Sanctum Token
     */
    public function login(Request $request): JsonResponse
    {
        // 1. Validate incoming request parameters
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Locate user in the database by email
        $user = User::where('email', $request->email)->first();

        // 3. Authenticate credentials and verify encrypted password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password credentials.'
            ], 401); // 401 Unauthorized
        }

        // 4. Generate plain text authentication token via Laravel Sanctum
        $token = $user->createToken('AdminDashboardToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ], 200);
    }

    /**
     * Retrieve Currently Authenticated User Context
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ], 200);
    }

    /**
     * Revoke Current Access Token (Logout)
     */
    public function logout(Request $request): JsonResponse
    {
        // Destruct the active token to secure the session
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully. Token revoked.'
        ], 200);
    }
}
