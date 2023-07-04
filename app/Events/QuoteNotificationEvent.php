<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuoteNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $author;
    public $quoteId;

    public function __construct(mixed $author,int $quoteId)
    {
        $this->quoteId = $quoteId;
        $this->author = $author;
    }
  
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notifications.'.$this->author['id']),
        ];
    }
}
