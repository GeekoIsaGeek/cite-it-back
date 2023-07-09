<?php

namespace App\Actions;

use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\AnonymousNotifiable;

class SendVerificationEmail
{
	public function handle(mixed $user, string $email): void
	{
		$verificationUrl = URL::temporarySignedRoute(
			'verification.verify',
			now()->addMinutes(60),
			[
				'id'   => $user->id,
				'hash' => sha1($email),
				'email' => $email
			]
		);
		$notifiable = new AnonymousNotifiable;
        $notifiable->route('mail', $email);
		
		$notifiable->notify(new VerifyEmail($user, $verificationUrl));
	}
}
