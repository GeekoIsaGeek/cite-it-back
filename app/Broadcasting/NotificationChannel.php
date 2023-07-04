<?php

namespace App\Broadcasting;

use App\Models\User;

class NotificationChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, int|string $userId): bool
    {
        return  (int) $user->id === (int) $userId;
    }
}
