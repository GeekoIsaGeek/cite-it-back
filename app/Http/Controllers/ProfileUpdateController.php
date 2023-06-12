<?php

namespace App\Http\Controllers;

use App\Actions\SendVerificationEmail;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProfileUpdateController extends Controller
{
	public function updateProfile(UpdateProfileRequest $request, SendVerificationEmail $sendVerificationEmail): JsonResponse
	{
		try {
			$validated = $request->validated();
			$user = User::where('id', $validated['id'])->first();
			$credentials = Arr::except($validated, 'id');

			if (array_key_exists('password', $credentials)) {
				$credentials['password'] = bcrypt($credentials['password']);
			}

			if (array_key_exists('profile_picture', $credentials)) {
				$profilePicture = $request->file('profile_picture');
				$folderPath = 'public/users/' . $user->id;
				$imageName = 'avatar.' . $profilePicture->getClientOriginalExtension();
				$imagePath = $profilePicture->storeAs($folderPath, $imageName);
				$existingAvatar = auth()->user()->profile_picture;

				if ($existingAvatar) {
					Storage::delete($existingAvatar);
				}
				$credentials['profile_picture'] = $imagePath;
			}

			if (array_key_exists('email', $credentials)) {
				$user->update($credentials);
				$sendVerificationEmail->handle($user);
				return response()->json(['message' => 'Email has been updated'], 200);
			}
			$user->update($credentials);
			return response()->json(['message' => 'Your profile has been updated'], 200);
		} catch(Throwable $error) {
			return response()->json(['error' => trans('errors.invalid_credentials'), 'exactErrorMessage' => $error->getMessage()], 400);
		}
	}
}
