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
    public $notification;
    public $interactantId;
    public $receiverId;

    public function __construct(mixed $notification, int $recieverId, int $interactantId)
    {
        $this->notification = $notification;
        $this->interactantId= $interactantId;
        $this->receiverId = $recieverId;
    }
  
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notifications.'.$this->receiverId),
        ];
    }
}
