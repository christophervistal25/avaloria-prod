<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedeemHistory extends Model
{
    

    protected $fillable = [
        'code',
        'character_id',
        'account_name',
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function character()
    {
        return $this->belongsTo(Character::class, 'character_id', 'm_idPlayer');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_name', 'account');
    }

    public function redeemCode()
    {
        return $this->belongsTo(RedeemCode::class, 'code', 'code');
    }
}
