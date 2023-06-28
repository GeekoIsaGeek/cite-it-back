<?php

namespace App\Http\Requests\Movie;

use App\Http\Requests\BaseRequest;
use App\Models\Movie;
use App\Rules\UniqueJsonField;

class StoreMovieRequest extends BaseRequest
{
	public function rules(): array
	{
		return [
			'name'           => ['required', 'string', 'max:255', new UniqueJsonField(Movie::class, 'name', 'en')],
			'name_ka'        => ['required', 'string', 'max:255', new UniqueJsonField(Movie::class, 'name', 'ka')],
			'description'    => ['required', 'string', 'max:1000'],
			'description_ka' => ['required', 'string', 'max:1000'],
			'release_date'   => ['required', 'string', 'numeric'],
			'poster'         => ['required', 'file', 'mimes:png,jpg,jpeg,svg'],
			'director'       => ['required', 'string', 'max:60'],
			'director_ka'    => ['required', 'string', 'max:60'],
			'genre'          => ['required'],
		];
	}
}
