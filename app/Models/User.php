<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\PasswordResetEmail;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
	use HasApiTokens;

	use HasFactory;

	use Notifiable;

	protected $guarded = [];

	protected $hidden = [
		'password',
		'remember_token',
	];

	public function sendPasswordResetNotification($token): void //  This must be defined in a model
	{
		$url = URL::to('/api/reset-password') . '/' . $token . '/' . $this->email;
		$this->notify(new PasswordResetEmail($this, $url));
	}

	protected $casts = [
		'email_verified_at' => 'datetime',
		'password'          => 'hashed',
	];
}
