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
    public $notification;

    public function __construct(mixed $notification, mixed $author)
    {
        $this->notification = $notification;
        $this->author = $author;
    }
  
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notifications.'.$this->notification['user_id']),
        ];
    }
}
