<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\StoreMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
	public function index(): JsonResponse
	{
		$movies = Movie::all();
		return response()->json($movies, 200);
	}

	public function store(StoreMovieRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$existingMovie = Movie::where('name->en', $validated['name'])->first();
		if ($existingMovie) {
			return response()->json(['error' => trans('errors.movie_already_exists')], 400);
		}
		$validated['poster'] = $request->file('poster')->store('posters', 'public');
		$movie = Movie::create(
			[
				'name'         => ['en'=>$validated['name'], 'ka'=> $validated['name_ka']],
				'director'     => ['en'=>$validated['director'], 'ka'=> $validated['director_ka']],
				'description'  => ['en'=>$validated['description'], 'ka'=>$validated['description_ka']],
				'genre'        => [$validated['genre']],
				'release_date' => $validated['release_date'],
				'poster'       => $validated['poster'],
			]
		);
		if ($movie) {
			return response()->json($movie, 201);
		} else {
			return response()->json(['error' => trans('errors.could_not_be_added')], 400);
		}
	}

	public function destroy(int $id)
	{
		if (Movie::where('id', $id)->exists()) {
			Movie::destroy($id);
			return response()->json([], 200);
		} else {
			return response()->json([], 404);
		}
	}
}
