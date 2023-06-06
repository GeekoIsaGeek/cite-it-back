<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\SendResetEmailRequest;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
	public function sendResetLink(SendResetEmailRequest $request): JsonResponse
	{
		$validatedEmail = $request->validated()['email'];
		$status = Password::sendResetLink(['email'=>$validatedEmail]);

		if ($status === Password::RESET_LINK_SENT) {
			return response()->json(['message'=>'Email has been sent successfully'], 200);
		}
	}

	public function redirectToResetForm(string $token, string $email): RedirectResponse
	{
		$resetFormUrl = config('client-app.url') . '/update-password';
		return redirect()->away($resetFormUrl . '?token=' . $token . '&email=' . $email);
	}

	public function updatePassword(UpdatePasswordRequest $request): JsonResponse |RedirectResponse
	{
		$validated = $request->validated();

		$status = Password::reset(
			$validated,
			function (User $user, string $password) {
				$user->forceFill([
					'password' => Hash::make($password),
				])->setRememberToken(Str::random(60));

				$user->save();

				event(new PasswordReset($user));
			}
		);

		if ($status === Password::PASSWORD_RESET) {
			return redirect(config('client-app.url') . '/password-updated');
		} else {
			return response()->json(['status'=> $status]);
		}
	}
}
