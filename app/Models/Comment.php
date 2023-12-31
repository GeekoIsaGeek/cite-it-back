<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $with = ['author'];

    public function author():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quote():BelongsTo
    {
        return $this->belongsTo(Quote::class, 'quote_id');
    }
}
