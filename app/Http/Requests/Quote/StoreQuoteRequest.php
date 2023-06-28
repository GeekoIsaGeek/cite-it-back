<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuoteRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'id'       => ['required', Rule::exists('movies', 'id')],
			'quote'    => ['required', 'string', 'max:255'],
			'quote_ka' => ['required', 'string', 'max:255'],
			'image'    => ['required', 'file', 'mimes:jpeg,png,webp,svg'],
		];
	}
}
