<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
	public function sendResetLink(Request $request): JsonResponse
	{
		$request->validate(['email' => 'required|email|exists:users,email']);

		$status = Password::sendResetLink(
			$request->only('email')
		);

		if ($status === Password::RESET_LINK_SENT) {
			return response()->json(['message'=>'Email has been sent successfully'], 200);
		}
	}

	public function redirectToResetForm(string $token, string $email): RedirectResponse
	{
		$resetFormUrl = env('CLIENT_APP_URL') . '/update-password';
		return redirect()->away($resetFormUrl . '?token=' . $token . '&email=' . $email);
	}

	public function updatePassword(Request $request): JsonResponse |RedirectResponse
	{
		$request->validate([
			'token'    => 'required',
			'email'    => 'required|email',
			'password' => 'required|min:8|confirmed',
		]);

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function (User $user, string $password) {
				$user->forceFill([
					'password' => Hash::make($password),
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		if ($status === Password::PASSWORD_RESET) {
			return redirect(env('CLIENT_APP_URL') . '/password-updated');
		} else {
			return response()->json(['status'=> $status]);
		}
	}
}
