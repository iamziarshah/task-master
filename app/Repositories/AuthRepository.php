<?php

namespace App\Repositories;

use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface
{
    public function login($request)
    {
        if (Auth::attempt(['email' => strtolower($request->email), 'password' => $request->password, 'status' => 'active'])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken(env('API_TOKEN_KEY', 'TASKMASTERAPP'))->plainTextToken;
            $success['name'] =  $user->full_name;

            return $success;
        }

        return [];
    }
}
