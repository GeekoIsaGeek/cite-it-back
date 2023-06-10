<?php

namespace App\Http\Controllers;

use App\Actions\SendVerificationEmail;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Throwable;

class ProfileUpdateController extends Controller
{
	public function updateProfile(UpdateProfileRequest $request, SendVerificationEmail $sendVerificationEmail): JsonResponse
	{
		try {
			$validated = $request->validated();
			$user = User::where('id', $validated['id'])->first();
			$validated['password'] = bcrypt($validated['password']);

			if (array_key_exists('email', $validated)) {
				$user->update(Arr::except($validated, 'id'));
				$sendVerificationEmail->handle($user);
				return response()->json(['message' => 'Your profile has been updated and email verification email has been sent'], 200);
			}
			$user->update(Arr::except($validated, 'id'));
			return response()->json(['message' => 'Your profile has been updated'], 200);
		} catch(Throwable $error) {
			return response()->json(['error' => trans('errors.invalid_credentials'), 'exactErrorMessage' => $error->getMessage()], 400);
		}
	}
}
