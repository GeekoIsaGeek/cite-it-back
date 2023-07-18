<?php

namespace App\Http\Controllers;

use App\Events\CommentAddedEvent;
use App\Events\QuoteLikedEvent;
use App\Events\QuoteNotificationEvent;
use App\Helpers\NotificationDataExtractor;
use App\Http\Requests\Quote\AddCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;

class LikeCommentController extends Controller
{
	
	public function addLike(int $quoteId): void
	{
		$quote = Quote::findOrFail($quoteId);
		$user = auth()->user();
		$hasAlreadyLiked = $quote->likes()->where('user_id', $user->id)->exists();
		if ($hasAlreadyLiked) {
			$quote->likes()->detach($user->id);
		}else{
			$quote->likes()->attach($user->id);
			$this->createNotification( $user, $quote, 'like');	
		}
		event(new QuoteLikedEvent($quote->id));
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
		$this->createNotification($user, $quote, 'comment');
	}
	
	private function createNotification(mixed $user, mixed $quote, string $action): void
	{	
		if($user->id !== $quote->user_id) {
			$interactant = NotificationDataExtractor::extractUserData($user);	
			$quoteCreatorId = $quote->movie->author->id;
			$notification = Notification::create([
				'quote_id' => $quote->id,
				'user_id' => $quoteCreatorId,
				'action' => $action,
				'author' => $interactant['username'],
				'author_avatar' => $interactant['profile_picture']
			]); 
			event(new QuoteNotificationEvent($notification, $quoteCreatorId, $user->id));
		}
	}
}
