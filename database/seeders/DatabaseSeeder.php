<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	public function run(): void
	{
		$user = User::factory()->create();
		$movies = Movie::factory(4)->create(['user_id'=> $user->id]);
		foreach ($movies as $movie) {
			Quote::factory()->create(['user_id'=>$movie->user_id, 'movie_id' => $movie->id]);
		}
	}
}
