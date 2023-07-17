<?php

namespace App\Http\Requests\Quote;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuoteRequest extends FormRequest
{
	protected function prepareForValidation(): void
	{
		$this->merge([
			'quote' => ['en' => $this->quote, 'ka' => $this->quote_ka]
		]);
	}

	public function rules(): array
	{
		return [
			'id'       => ['required', Rule::exists('movies', 'id')],
			'quote.en'    => ['required', 'string', 'max:255'],
			'quote.ka' => ['required', 'string', 'max:255'],
			'image'    => ['required', 'file', 'mimes:jpeg,png,webp,svg'],
		];
	}
}
