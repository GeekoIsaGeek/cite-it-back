<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends BaseRequest
{
	public function rules(): array
	{
		return [
			'username' => ['required', 'min:3', 'max:15', 'only_lowercase', Rule::unique('users', 'username')],
			'email'    => ['required', 'email', Rule::unique('users', 'email')],
			'password' => ['required', 'min:8', 'max:15', 'only_lowercase', 'confirmed'],
		];
	}
}
