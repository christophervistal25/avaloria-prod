<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RedeemCodeDetail extends Model
{
    protected $fillable = [
        'redeem_code_id',
        'item_name',
        'item_description',
        'item_quantity',
        'item_code'
    ];

    public function redeemCode(): BelongsTo
    {
        return $this->belongsTo(RedeemCode::class);
    }
}