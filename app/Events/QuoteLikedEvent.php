<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuoteLikedEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $quoteId;
	public function __construct(int $quoteId)
	{
		$this->quoteId = $quoteId;
	}

	public function broadcastOn(): array
	{
		return [
			new Channel('likes'),
		];
	}
}
