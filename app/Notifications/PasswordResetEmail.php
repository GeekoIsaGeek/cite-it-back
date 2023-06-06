<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetEmail extends Notification
{
	use Queueable;

	private $user;

	private $resetUrl;

	public function __construct($user, $resetUrl)
	{
		$this->user = $user;
		$this->resetUrl = $resetUrl;
	}

	public function via(object $notifiable): array
	{
		return ['mail'];
	}

	public function toMail(object $notifiable): MailMessage
	{
		return (new MailMessage())
		->view('emails.password-reset', [
			'user'=> $this->user,
			'url' => $this->resetUrl,
		]);
	}
}
