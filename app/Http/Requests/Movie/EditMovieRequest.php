<?php

namespace App\Http\Requests\Movie;

use App\Http\Requests\BaseRequest;

class EditMovieRequest extends BaseRequest
{
	public function rules(): array
	{
		return [
			'name'           => ['sometimes', 'string', 'max:255'],
			'name_ka'        => ['sometimes', 'string', 'max:255'],
			'description'    => ['sometimes', 'string', 'max:1000'],
			'description_ka' => ['sometimes', 'string', 'max:1000'],
			'release_date'   => ['sometimes', 'string', 'numeric'],
			'poster'         => ['sometimes', 'file', 'mimes:png,jpg,jpeg,svg', 'nullable'],
			'director'       => ['sometimes', 'string', 'max:60'],
			'director_ka'    => ['sometimes', 'string', 'max:60'],
			'genre'          => ['sometimes'],
		];
	}
}
