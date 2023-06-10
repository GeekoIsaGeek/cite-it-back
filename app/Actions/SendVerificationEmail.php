<?php

namespace App\Actions;

use App\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

class SendVerificationEmail
{
	public function handle(mixed $user): void
	{
		$verificationUrl = URL::temporarySignedRoute(
			'verification.verify',
			now()->addMinutes(60),
			[
				'id'   => $user->id,
				'hash' => sha1($user->email),
			]
		);
		$user->notify(new VerifyEmail($user, $verificationUrl));
	}
}
