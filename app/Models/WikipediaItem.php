<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WikipediaItem extends Model
{
    protected $fillable = [
        'wikipedia_id',
        'title',
        'description',
        'order'
    ];

    public function wikipedia(): BelongsTo
    {
        return $this->belongsTo(Wikipedia::class);
    }
}