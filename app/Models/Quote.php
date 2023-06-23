<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $casts = [
		'quote'=> 'array',
	];

	public function movie(): BelongsTo
	{
		return  $this->belongsTo(Movie::class);
	}

	public function author(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
