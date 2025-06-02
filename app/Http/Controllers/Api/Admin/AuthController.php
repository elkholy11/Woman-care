<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Http\Requests\Admin\Auth\AdminLoginRequest;
use App\Http\Requests\Admin\Auth\AdminRegisterRequest;
use App\Http\Resources\Admin\AdminResource;


class AuthController extends Controller
{
    public function register(AdminRegisterRequest $request)
    {
        $admin = Admin::create([
            'name' => $request->validated()['name'],
            'email' => $request->validated()['email'],
            'password' => bcrypt($request->validated()['password']),
        ]);

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'admin' => new AdminResource($admin),
            'token' => $token
        ], 201);
    }

    public function login(AdminLoginRequest $request)
    {
        
        if (!Auth::guard('admin')->attempt($request->validated())) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $admin = Auth::guard('admin')->user();
        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'admin' => new AdminResource($admin),
            'token' => $token
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
