<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\{JsonResponse, Response};
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{Auth, Hash, Validator};

class AuthController extends Controller
{
    public function login(): Response|JsonResponse
    {
        $validateUser = Validator::make(request()->all(), [
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        return match (true) {
            $validateUser->fails() => response()->json([
                'status'  => false,
                'message' => 'validation error',
                'errors'  => $validateUser->errors(),
            ], Response::HTTP_METHOD_NOT_ALLOWED),
            !Auth::attempt(request()->only(['email', 'password'])) => response()->json([
                'status'  => false,
                'message' => 'Email & Password does not match with our record.',
            ], Response::HTTP_UNAUTHORIZED),
            default => response()->json([
                'status'  => true,
                'message' => 'User Logged In Successfully',
                'user'    => auth()->user(),
                'token'   => auth()->user()->createToken('API TOKEN')->plainTextToken,
            ], Response::HTTP_OK)
        };
    }

    public function register(): Response|JsonResponse
    {
        $validateUser = Validator::make(request()->all(), [
            'name'              => ['required'],
            'email'             => ['required', 'email', 'unique:users,email'],
            'password'          => ['required', 'min:6'],
            'confirmedPassword' => ['required', 'same:password'],
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'validation error',
                'errors'  => $validateUser->errors(),
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        $validateUser = Arr::except($validateUser->getData(), 'confirmedPassword');

        $validateUser['password'] = Hash::make(request('password'));

        Auth::login(User::create($validateUser));

        return response()->json([
            'status'  => true,
            'message' => 'User Logged In Successfully',
            'user'    => auth()->user(),
            'token'   => auth()->user()->createToken('API TOKEN')->plainTextToken,
        ], Response::HTTP_CREATED);
    }
}
