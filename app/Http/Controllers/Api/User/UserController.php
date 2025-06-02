<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Requests\User\ProfileRequest;

class UserController extends Controller
{
    public function profile()
    {
        return new UserResource(Auth::user());
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validated());
        return new UserResource($user);
    }
}