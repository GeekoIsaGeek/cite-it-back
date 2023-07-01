<?php

namespace App\Http\Controllers;

use App\Events\QuoteHasBeenLiked;
use App\Models\Quote;

class QuoteInteractions extends Controller
{
	public function addLike(int $quoteId): void
	{
		$quote = Quote::findOrFail($quoteId);
		$userId = auth()->user()->id;
		$hasAlreadyLiked = $quote->likes()->where('user_id', $userId)->exists();
		if (!$hasAlreadyLiked) {
			$quote->likes()->attach($userId);
			event(new QuoteHasBeenLiked($quote->load('likes')));
		}
	}
}
