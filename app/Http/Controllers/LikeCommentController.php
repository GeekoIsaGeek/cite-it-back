<?php

namespace App\Http\Controllers;

use App\Events\CommentAddedEvent;
use App\Events\QuoteLikedEvent;
use App\Http\Requests\Quote\AddCommentRequest;
use App\Models\Comment;
use App\Models\Quote;

class LikeCommentController extends Controller
{
	public function addLike(int $quoteId): void
	{
		$quote = Quote::findOrFail($quoteId);
		$userId = auth()->user()->id;
		$hasAlreadyLiked = $quote->likes()->where('user_id', $userId)->exists();
		if (!$hasAlreadyLiked) {
			$quote->likes()->attach($userId);
			event(new QuoteLikedEvent($quote->load('likes')));
		}
	}

	public function addComment(AddCommentRequest $request,int $quoteId): void
	{
		$validatedComment = $request->validated()['comment'];
		$userId = auth()->user()->id;
		$quote = Quote::findOrFail($quoteId);
		Comment::create([
			'user_id' => $userId,
			'quote_id' => $quote->id,
			'comment' => $validatedComment
		]);
		event(new CommentAddedEvent($quote->load('comments')));
	}
}
