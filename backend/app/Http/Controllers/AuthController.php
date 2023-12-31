<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(): Response|JsonResponse
    {
        $validateUser = Validator::make(request()->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'confirmedPassword' => ['required', 'same:password'],
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors(),
            ], 401);
        }

        $validateUser = Arr::except($validateUser->getData(), 'confirmedPassword');

        $validateUser['password'] = Hash::make(request('password'));

        Auth::login(User::create($validateUser));

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('API TOKEN')->plainTextToken,
        ], 201);
    }
}
