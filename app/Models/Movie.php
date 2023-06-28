<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $casts = [
		'genre'       => 'array',
		'name'        => 'array',
		'description' => 'array',
		'director'    => 'array',
	];

	protected $hidden = ['quote_id', 'user_id'];

	protected $with = ['author'];

	public function quotes(): HasMany
	{
		return $this->hasMany(Quote::class);
	}

	public function author(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}
