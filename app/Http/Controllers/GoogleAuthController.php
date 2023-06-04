<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
	public function redirectToProvider(): RedirectResponse
	{
		return Socialite::driver('google')->redirect();
	}

	public function handleCallback(): string | RedirectResponse
	{
		$googleUser = Socialite::driver('google')->user();

		$user = User::updateOrCreate([
			'google_id' => $googleUser->id,
		], [
			'username'             => $googleUser->name,
			'email'                => $googleUser->email,
			'google_token'         => $googleUser->token,
			'google_refresh_token' => $googleUser->refreshToken,
		]);
		auth()->login($user);
		return redirect(env('CLIENT_APP_URL'));
	}
}
