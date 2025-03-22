<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPaypalDonate extends Model
{
    use HasUuids;
    use HasFactory;
    protected $fillable = [
        'donate_paypal_id',
        'user_id',
        'transaction_id',
        'reference_number',
        'status',
        'paypal_response',
        'account',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function donatePaypal()
    {
        return $this->belongsTo(DonatePaypal::class, 'donate_paypal_id', 'id');
    }

    public function account_information()
    {
        return $this->belongsTo(Account::class, 'account', 'account');
    }
}
