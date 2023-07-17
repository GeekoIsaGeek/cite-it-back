<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SendResetEmailRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'email' => ['required','email','exists','users','email']
		];
	}
}
