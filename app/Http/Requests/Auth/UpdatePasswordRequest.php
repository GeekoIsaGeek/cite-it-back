<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'token'    => 'required',
			'email'    => 'required|email',
			'password' => 'required|min:8|confirmed',
		];
	}
}
