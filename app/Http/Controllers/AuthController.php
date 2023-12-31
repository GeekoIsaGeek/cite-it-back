<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use App\Actions\SendVerificationEmail;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	public function getUser(Request $request): mixed
	{
		return $request->user()->load(['notifications' => function($query){
			$query->orderBy('created_at','desc');
		}]);
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$nameOrEmail = filter_var($validated['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
		$request->merge([$nameOrEmail => $validated['username']]);

		if (auth()->attempt($request->only([$nameOrEmail, 'password']), (bool)$request->has('remember'))) {
			$user = User::where($nameOrEmail, $validated['username'])->first();
			return response()->json(['user'=> $user->only('username', 'email')], 200);
		} else {
			return response()->json(['error' => trans('errors.invalid_credentials')], 401);
		}
	}

	public function register(RegistrationRequest $request, SendVerificationEmail $sendVerificationEmail): JsonResponse
	{
		$validated = $request->validated();
		$user = User::create([...$validated, 'password'=> bcrypt($validated['password'])]);
		$sendVerificationEmail->handle($user, $user->email);
		return response()->json(['message'=> 'Your account has been created successfully'], 201);
	}

	public function logout(): void
	{
		auth()->logout();
		session()->flush();
	}

	public function verifyEmail(int $id, string $hash, string $email): RedirectResponse
	{
		$user = User::findOrFail($id);
		$baseUrl = config('client-app.url');
		$redirectUrl = $baseUrl . '/auth/verification-succeed';

		$user->update(['email'=> $email]);
		
		if ($user->hasVerifiedEmail()) {
			$redirectUrl = $baseUrl . '/email-updated';
		}
		
		if ($user->markEmailAsVerified()) {
			event(new Verified($user));
		}
		return redirect($redirectUrl);
	}
}
