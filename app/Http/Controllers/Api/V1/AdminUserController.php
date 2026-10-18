<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'role', 'created_at')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    public function store(AdminUserRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Admin created successfully.',
            'data' => $user,
        ], 201);
    }

    public function update(AdminUserRequest $request, User $admin): JsonResponse
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Admin updated successfully.',
            'data' => $admin,
        ]);
    }

    public function destroy(AdminUserRequest $request, User $admin): JsonResponse
    {
        if ($request->user()->id === $admin->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot delete your own account.',
            ], 422);
        }

        $admin->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Admin removed successfully.',
        ]);
    }
}
