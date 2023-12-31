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
		$movies = Movie::orderBy('release_date', 'desc')->with(['quotes'])->get();
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
		$movie = Movie::create([...$validated,'user_id' => auth()->user()->id]);

		if ($movie) {
			return response()->json($movie->load(['quotes','author']), 201);
		} else {
			return response()->json(['error' => trans('errors.could_not_be_added')], 400);
		}
	}

	public function destroy(int|string $id): JsonResponse
	{	
		$movie = Movie::find((int)$id);
		$this->authorize('destroy',$movie);
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
		$this->authorize('update',$movie);
		$validated = $request->validated();
		$updatedValidatedParameters = $this->processUpdateMovieRequestParameters($validated, $movie, $request);

		$movie->update($updatedValidatedParameters);
		return response()->json($movie->load('quotes'), 200);
	}

	public function getPaginatedMovies():JsonResponse
	{
		$movies = Movie::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->with('quotes')->paginate(6); 	
		return response()->json($movies);
	}

	private function processUpdateMovieRequestParameters($validated, $movie, $request): array 
	{
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

		return $validated;
	}
}
