<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $nameOrEmail = filter_var($validated['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$nameOrEmail => $validated['username']]);

        if(auth()->attempt($request->only([$nameOrEmail, 'password']), (bool)$request->has('remember'))) {
            return response()->json(['message'=> 'Successful login'], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function register(RegistrationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        if($user) {
            return response()->json(['message'=> 'User with this email already exists'], 400);
        } else {
            $newUser = User::create([...$validated, 'password'=> bcrypt($validated['password'])]);
            return response()->json(['message' => 'User has been registered successfully'], 201);
        }
    }
}
