<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$genres = ['Action', 'Comedy', 'Drama', 'Horror', 'Romance', 'Sci-Fi'];

		return [
			'name'           => ['en' => fake()->text(50), 'ka' => fake('ka_GE')->realText(50)],
			'release_date'   => fake()->year(),
			'genre'          => fake()->randomElements($genres, 2),
			'director'       => ['en' => fake()->name(), 'ka' => fake('ka_GE')->name()],
			'description'    => ['en' => fake()->realText(), 'ka' => fake('ka_GE')->realText()],
			'poster'         => fake()->imageUrl(),
		];
	}
}
