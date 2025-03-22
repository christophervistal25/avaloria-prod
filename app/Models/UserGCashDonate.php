<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserGCashDonate extends Model
{
    use HasUuids;
    use HasFactory;
    protected $fillable = [
        'donate_g_cash_id',
        'user_id',
        'transaction_id',
        'reference_number',
        'amount',
        'status',
        'account',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function donateGCash()
    {
        return $this->belongsTo(DonateGCash::class, 'donate_g_cash_id', 'id');
    }

    public function account_information()
    {
        return $this->belongsTo(Account::class, 'account', 'account');
    }
}
