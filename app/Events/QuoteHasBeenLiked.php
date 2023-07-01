<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuoteHasBeenLiked implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $quote;

	public function __construct(mixed $quote)
	{
		$this->quote = $quote;
	}

	public function broadcastOn(): array
	{
		return [
			new Channel('likes'),
		];
	}
}
