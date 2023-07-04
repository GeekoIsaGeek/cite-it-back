<?php

namespace App\Helpers;

class NotificationDataExtractor
{
    public static function extractUserData($user): mixed
    {
        return [
            'profile_picture' => $user->profile_picture,
            'username' => $user->username,
            'id' => $user->id
        ];
    }
}