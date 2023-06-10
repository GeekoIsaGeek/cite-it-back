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
		$resetFormUrl = config('client-app.url') . '/auth/update-password';
		return redirect()->away($resetFormUrl . '?token=' . $token . '&email=' . $email);
	}

	public function updatePassword(UpdatePasswordRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$user = User::where('email', $validated['email'])->first();

		$status = Password::reset(
			$validated,
			function (User $user, string $password) {
				$user->forceFill([
					'password' => Hash::make($password),
				])->setRememberToken(Str::random(60));

				$user->save();
			}
		);

		if ($status === Password::PASSWORD_RESET) {
			event(new PasswordReset($user));
			return response()->json(['message' => 'Password has been recovered successfully'], 200);
		} else {
			return response()->json(['status' => $status], 400);
		}
	}
}
