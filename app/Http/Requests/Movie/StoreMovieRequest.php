<?php

namespace App\Http\Requests\Movie;

use App\Http\Requests\BaseRequest;
use App\Models\Movie;
use App\Rules\UniqueJsonField;

class StoreMovieRequest extends BaseRequest
{
	protected function prepareForValidation(): void
	{
		$this->merge([
			'name' => ['en' => $this->name, 'ka' => $this->name_ka],
			'description' => ['en' => $this->description, 'ka' => $this->description_ka],
			'director' => ['en' => $this->director, 'ka' => $this->director_ka],
		]);
	}

	public function rules(): array
	{
		return [
			'name.en'           => ['required', 'string', 'max:255', new UniqueJsonField(Movie::class, 'name', 'en')],
			'name.ka'        => ['required', 'string', 'max:255', new UniqueJsonField(Movie::class, 'name', 'ka')],
			'description.en'    => ['required', 'string', 'max:1000'],
			'description.ka' => ['required', 'string', 'max:1000'],
			'release_date'   => ['required', 'string', 'numeric'],
			'poster'         => ['required', 'file', 'mimes:png,jpg,jpeg,svg'],
			'director.en'       => ['required', 'string', 'max:60'],
			'director.ka'    => ['required', 'string', 'max:60'],
			'genre'          => ['required'],
		];
	}
}
