<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $hidden = ['movie_id'];

	protected $casts = [
		'quote'=> 'array',
	];

	protected $with = ['movie', 'comments', 'likes'];

	public function movie(): BelongsTo
	{
		return  $this->belongsTo(Movie::class);
	}

	public function comments(): HasMany
	{
		return $this->hasMany(Comment::class);
	}

	public function likes(): BelongsToMany
	{
		return $this->belongsToMany(User::class);
	}
}
