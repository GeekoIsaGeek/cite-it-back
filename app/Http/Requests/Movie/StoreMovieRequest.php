<?php

namespace App\Http\Requests\Movie;

use App\Http\Requests\BaseRequest;

class StoreMovieRequest extends BaseRequest
{
	public function rules(): array
	{
		return [
			'name'           => ['required', 'string', 'max:255'],
			'name_ka'        => ['required', 'string', 'max:255'],
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
