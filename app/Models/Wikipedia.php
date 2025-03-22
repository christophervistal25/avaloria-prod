<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wikipedia extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'created_by',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(WikipediaItem::class)->orderBy('order');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}