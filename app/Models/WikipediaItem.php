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
        'order',
        'image',
    ];

    public $appends = [
        'image_url',
    ];

    public function wikipedia(): BelongsTo
    {
        return $this->belongsTo(Wikipedia::class);
    }


    /**
     * Get the image URL.
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return null;
    }
}