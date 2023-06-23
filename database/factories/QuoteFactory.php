<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
	public function definition(): array
	{
		return [
			'quote' => ['en'=> fake()->realText(60), 'ka'=> fake('ka_GE')->realText(60)],
			'image' => fake()->image(),
		];
	}
}
