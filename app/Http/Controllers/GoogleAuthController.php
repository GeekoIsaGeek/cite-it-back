<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
	public function redirectToProvider(): RedirectResponse
	{
		return Socialite::driver('google')->redirect();
	}

	public function handleCallback(): string | RedirectResponse
	{
		try {
			$googleUser = Socialite::driver('google')->user();
			if (User::where('email', $googleUser->email)->where('google_id', null)->first()) {
				return trans('errors.email_is_taken');
			}

			$user = User::updateOrCreate([
				'google_id' => $googleUser->id,
			], [
				'username'             => $googleUser->name,
				'email'                => $googleUser->email,
				'google_token'         => $googleUser->token,
				'google_refresh_token' => $googleUser->refreshToken,
			]);
			auth()->login($user);
			return redirect(config('client-app.url'));
		} catch (Throwable $error) {
			return $error->getMessage();
		}
	}
}
