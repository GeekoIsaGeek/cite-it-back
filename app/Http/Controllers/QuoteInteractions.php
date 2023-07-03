<?php

namespace App\Http\Controllers;

use App\Events\CommentHasBeenAdded;
use App\Events\QuoteHasBeenLiked;
use App\Http\Requests\Quote\AddCommentRequest;
use App\Models\Comment;
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
	public function addComment(AddCommentRequest $request,int $quoteId)
	{
		$validatedComment = $request->validated()['comment'];
		$userId = auth()->user()->id;
		$quote = Quote::findOrFail($quoteId);
		Comment::create([
			'user_id' => $userId,
			'quote_id' => $quote->id,
			'comment' => $validatedComment
		]);
		event(new CommentHasBeenAdded($quote->load('comments')));
		
		return response()->json($quote);
	}
}
