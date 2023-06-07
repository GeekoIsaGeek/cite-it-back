<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification
{
	use Queueable;

	private $user;

	private $verificationUrl;

	public function __construct($user, $verificationUrl)
	{
		$this->user = $user;
		$this->verificationUrl = $verificationUrl;
	}

	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	public function toMail(object $notifiable): MailMessage
	{
		return (new MailMessage())
		->view('emails.verification', [
			'user'=> $this->user,
			'url' => $this->verificationUrl,
		]);
	}
}
