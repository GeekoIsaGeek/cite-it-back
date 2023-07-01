<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
	public function search(string $searchString): JsonResponse
	{
		$searchString = trim($searchString);
		if (str_starts_with($searchString, '#')) {
			$quotes = Quote::where('quote->en', 'like', '%' . substr($searchString, 1) . '%')->orWhere('quote->ka', 'like', '%' . substr($searchString, 1) . '%')->get();
			return response()->json($quotes, 200);
		}
		if (str_starts_with($searchString, '@')) {
			$movies = Movie::where('name->en', 'like', '%' . substr($searchString, 1) . '%')->orWhere('name->ka', 'like', '%' . substr($searchString, 1) . '%')->get();
			return response()->json($movies->load('quotes'), 200);
		}
		return response()->json([]);
	}
}
