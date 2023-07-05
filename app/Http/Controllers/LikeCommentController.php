<?php

namespace App\Http\Controllers;

use App\Events\CommentAddedEvent;
use App\Events\QuoteInteractionEvent;
use App\Events\QuoteLikedEvent;
use App\Events\QuoteNotificationEvent;
use App\Helpers\NotificationDataExtractor;
use App\Http\Requests\Quote\AddCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
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
			event(new QuoteLikedEvent());	
			$this->createNotification($user, $quote->id, 'like');
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
		event(new CommentAddedEvent($comment));
		$this->createNotification($user, $quote->id, 'comment');
	}
	
	private function createNotification(mixed $user, int $quoteId, string $action): void
	{
		$author = NotificationDataExtractor::extractUserData($user);	
		$notification = Notification::create([
			'quote_id' => $quoteId,
			'user_id' => $user->id,
			'action' => $action,
			'author' => $author['username'],
			'author_avatar' => $author['profile_picture']
		]); 

		event(new QuoteNotificationEvent($notification, $user->id));
	}

}
