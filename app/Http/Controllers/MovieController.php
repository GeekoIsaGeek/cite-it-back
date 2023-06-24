<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movie\EditMovieRequest;
use App\Http\Requests\Movie\StoreMovieRequest;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
	public function index(): JsonResponse
	{
		$movies = Movie::where('user_id', auth()->user()->id)->with(['quotes'])->get();
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
				'genre'        => $validated['genre'],
				'release_date' => $validated['release_date'],
				'poster'       => $validated['poster'],
				'user_id'      => auth()->user()->id,
			]
		);
		if ($movie) {
			return response()->json($movie->load('quotes'), 201);
		} else {
			return response()->json(['error' => trans('errors.could_not_be_added')], 400);
		}
	}

	public function destroy(int|string $id): JsonResponse
	{
		$id = (int)$id;
		$movie = Movie::find($id);
		if ($movie) {
			Storage::delete($movie->poster);
			$movie->delete();
			return response()->json([], 200);
		} else {
			return response()->json([], 404);
		}
	}

	public function update(EditMovieRequest $request, int $id): JsonResponse
	{
		$movie = Movie::findOrFail($id);
		$validated = $request->validated();

		if (array_key_exists('name', $validated) && array_key_exists('name_ka', $validated)) {
			$validated['name'] = ['en'=>$validated['name'], 'ka'=> $validated['name_ka']];
		}
		if (array_key_exists('director', $validated) && array_key_exists('director_ka', $validated)) {
			$validated['director'] = ['en'=>$validated['director'], 'ka'=> $validated['director_ka']];
		}
		if (array_key_exists('description', $validated) && array_key_exists('description_ka', $validated)) {
			$validated['description'] = ['en'=>$validated['description'], 'ka'=>$validated['description_ka']];
		}
		if (array_key_exists('poster', $validated)) {
			Storage::delete($movie->poster);
			$validated['poster'] = $request->file('poster')->store('posters', 'public');
		}

		$validated = array_filter($validated, function ($key) {
			return !preg_match('/_ka$/', $key);
		}, ARRAY_FILTER_USE_KEY);

		$movie->update($validated);
		return response()->json($movie, 200);
	}
}
