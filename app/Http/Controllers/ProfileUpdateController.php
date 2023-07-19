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
			$user = User::find($validated['id']);
			$credentials = Arr::except($validated, 'id');

			$processedParameters = $this->processRequestParameters($user,$credentials,$request, $sendVerificationEmail);		
			$user->update($processedParameters);

			$responseMessage = $request->email 
				? trans('messages.profile_updated') . trans('messages.email_is_sent') 	
				: trans('messages.profile_updated');
			
			return response()->json(['message' => $responseMessage, 'user' => $user], 200);
		} catch(Throwable $error) {
			return response()->json(['error' => $error->getMessage()], 400);
		}
	}

	private function processRequestParameters($user,$credentials,$request, $sendVerificationEmail): array
	{
		if (array_key_exists('password', $credentials)) {
			$credentials['password'] = bcrypt($credentials['password']);
		}

		if (array_key_exists('profile_picture', $credentials)) {
			$existingAvatar = $user->profile_picture;
			if ($existingAvatar) {
				Storage::delete($existingAvatar);
			}
			$imagePath = $request->file('profile_picture')->store('users/' . $user->id);
			$credentials['profile_picture'] = $imagePath;
		}
		
		if (array_key_exists('email', $credentials)) {
			$sendVerificationEmail->handle($user, $credentials['email']);
		}
		
		$credentials = array_filter($credentials,function($key){
			return $key !== 'email';
		},ARRAY_FILTER_USE_KEY);

		return $credentials;
	}
}
