<?php

namespace App\Http\Controllers;

use App\Events\CommentAddedEvent;
use App\Events\QuoteInteractionEvent;
use App\Events\QuoteLikedEvent;
use App\Events\QuoteNotificationEvent;
use App\Helpers\NotificationDataExtractor;
use App\Http\Requests\Quote\AddCommentRequest;
use App\Models\Comment;
use App\Models\Quote;

class LikeCommentController extends Controller
{
	
	public function addLike(int $quoteId): void
	{
		$quote = Quote::findOrFail($quoteId);
		$user = auth()->user();
		$hasAlreadyLiked = $quote->likes()->where('user_id', $user->id)->exists();
		if (!$hasAlreadyLiked) {
			$quote->likes()->attach($user->id);
			event(new QuoteLikedEvent($quote));	
			$this->fireQuoteNotificationEvent($user,$quote->id);			
		}
	}

	public function addComment(AddCommentRequest $request,int $quoteId): void
	{
		$validatedComment = $request->validated()['comment'];
		$user= auth()->user();
		$quote = Quote::findOrFail($quoteId);
		$comment = Comment::create([
			'user_id' => $user->id,
			'quote_id' => $quote->id,
			'comment' => $validatedComment
		]);
		event(new CommentAddedEvent($quote));
		$this->fireQuoteNotificationEvent($user,$quote->id);				
	}
	
	private function fireQuoteNotificationEvent($user,$quoteId): void 
	{
		$author = NotificationDataExtractor::extractUserData($user);
		event(new QuoteNotificationEvent($author,$quoteId));
	}
}
