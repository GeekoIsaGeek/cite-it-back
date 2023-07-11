<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class SearchController extends Controller
{
	public function search(string $searchString): JsonResponse
	{
		$formattedSearchString = substr(trim($searchString), 1);

		if (str_starts_with($searchString, '#')) {
			$quotes = Quote::where('quote->en', 'like', "%$formattedSearchString%")->orWhere('quote->ka', 'like', "%$formattedSearchString%")->get();
			return response()->json($quotes, 200);
		}
		if (str_starts_with($searchString, '@')) {
			$movies = Movie::where('name->en', 'like', "%$formattedSearchString%")->orWhere('name->ka', 'like',"%$formattedSearchString%")->get();
			return response()->json($movies->load('quotes'), 200);
		}
		return response()->json([]);
	}
}
