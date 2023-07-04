<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\PasswordResetEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\URL;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
	use HasApiTokens;

	use HasFactory;

	use Notifiable;

	protected $guarded = ['id'];

	protected $hidden = [
		'password',
		'remember_token',
	];

	public function sendPasswordResetNotification($token): void
	{
		$url = URL::to('/api/reset-password') . '/' . $token . '/' . $this->email;
		$this->notify(new PasswordResetEmail($this, $url));
	}

	public function movies(): HasMany
	{
		return $this->hasMany(Movie::class);
	}

	public function quotes(): HasManyThrough
	{
		return $this->hasManyThrough(Quote::class, Movie::class);
	}

	public function likedPosts(): BelongsToMany
	{
		return $this->belongsToMany(Quote::class);
	}

	public function notifications(): HasMany 
	{
		return $this->hasMany(Notification::class);
	}

	protected $casts = [
		'email_verified_at' => 'datetime',
		'password'          => 'hashed',
	];
}
