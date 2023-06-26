<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\StoreQuoteRequest;
use App\Http\Requests\Quote\UpdateQuoteRequest;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class QuoteController extends Controller
{
	public function index(): JsonResponse
	{
		$quotes = Quote::all();
		return response()->json($quotes, 200);
	}

	public function store(StoreQuoteRequest $request): JsonResponse
	{
		$validated = $request->validated();
		$directoryPath = '/quotes/' . $validated['quote'];

		$quote = Quote::create([
			'quote'    => ['en'=> $validated['quote'], 'ka'=> $validated['quote_ka']],
			'movie_id' => $validated['id'],
			'image'    => $request->file('image')->store($directoryPath),
			'user_id'  => auth()->user()->id,
		]);
		return response()->json($quote, 201);
	}

	public function destroy(int $quoteId): JsonResponse
	{
		$quote = Quote::find($quoteId);
		if (Storage::has($quote->image)) {
			Storage::delete($quote->image);
		}
		$quote->delete();
		return response()->json(null, 200);
	}

	public function update(UpdateQuoteRequest $request, int $id): JsonResponse
	{
		$validated = $request->validated();
		$quote = Quote::find($id);

		if (array_key_exists('quote', $validated) && array_key_exists('quote_ka', $validated)) {
			$validated['quote'] = ['en'=> $validated['quote'], 'ka'=> $validated['quote_ka']];
		}
		if (array_key_exists('image', $validated)) {
			Storage::delete($quote->image);
			$validated['image'] = $request->file('image')->store('/quotes/' . $validated['quote']['en']);
		}
		$quote->update([
			'quote'    => $validated['quote'],
			'image'    => $validated['image'],
			'movie_id' => $validated['id'],
			'user_id'  => auth()->user()->id,
		]);

		return response()->json($quote, 200);
	}
}
