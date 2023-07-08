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
		$quotes = Quote::orderBy('created_at', 'desc')->get();
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
		return response()->json($quote->load(['likes','comments']), 201);
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
		$updatedData = [];

		if (array_key_exists('quote', $validated) && array_key_exists('quote_ka', $validated)) {
			$updatedData['quote'] = ['en'=> $validated['quote'], 'ka'=> $validated['quote_ka']];
		}
		if (array_key_exists('image', $validated)) {
			Storage::delete($quote->image);
			$updatedData['image'] = $request->file('image')->store('/quotes/' . $quote->id);
		}
		$quote->update([
			...$updatedData,
			'movie_id' => $validated['id'],
			'user_id'  => auth()->user()->id,
		]);

		return response()->json($quote->load('likes'), 200);
	}

	public function getPaginatedQuotes(): JsonResponse
	{
		$quotes = Quote::orderBy('created_at','desc')->paginate(3);
		return response()->json($quotes,200);
	}
}
