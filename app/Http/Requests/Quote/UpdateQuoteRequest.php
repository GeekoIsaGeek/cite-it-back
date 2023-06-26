<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuoteRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'id'       => ['sometimes', Rule::exists('movies', 'id')],
			'quote'    => ['sometimes', 'string', 'max:255'],
			'quote_ka' => ['sometimes', 'string', 'max:255'],
			'image'    => ['sometimes', 'file', 'mimes:jpeg,png,webp,svg', 'nullable'],
		];
	}
}
