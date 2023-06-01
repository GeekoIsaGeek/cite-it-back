<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $nameOrEmail = filter_var($validated['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$nameOrEmail => $validated['username']]);

        if(auth()->attempt($request->only([$nameOrEmail, 'password']), (bool)$request->has('remember'))) {
            $user = User::where($nameOrEmail, $validated['username'])->first();
            return response()->json(['user'=> $user->only('username', 'email')], 200);
        } else {
            return response()->json(['error' => trans('errors.invalid_credentials')], 401);
        }
    }

    public function register(RegistrationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = User::where('email', $validated['email'])->first();
        if($user) {
            return response()->json(['error'=> trans('errors.email_is_taken')], 400);
        } else {
            $newUser = User::create([...$validated, 'password'=> bcrypt($validated['password'])]);
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                    'id'   => $newUser->id,
                    'hash' => sha1($newUser->email),
                ]
            );
            $newUser->notify(new VerifyEmail($newUser, $verificationUrl));
            return response()->json(["message"=> 'Your account has been created successfully'], 201);
        }
    }
    public function logout(): void
    {
        auth()->logout();
    }
    public function verifyEmail(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        if (!$user->hasVerifiedEmail() && $user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        return redirect(env('CLIENT_APP_URL').'/verification-succeed');
    }
}
