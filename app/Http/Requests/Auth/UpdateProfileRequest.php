<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends BaseRequest
{
	public function rules(): array
	{
		return [
			'id'              => ['required', Rule::exists('users', 'id')],
			'username'        => ['min:3', 'max:15', 'only_lowercase', Rule::unique('users', 'username')],
			'email'           => ['email', Rule::unique('users', 'email')],
			'password'        => ['min:8', 'max:15', 'only_lowercase', 'confirmed'],
			'profile_picture' => ['file', 'mimes:png,jpg,svg'],
		];
	}
}
