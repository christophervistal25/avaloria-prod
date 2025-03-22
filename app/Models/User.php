<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Billable;
    use HasFactory;
    use Notifiable;


    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function account()
    {
        return $this->hasOne(Account::class, 'account', 'username');
    }

    public function gCashDonates()
    {
        return $this->hasMany(UserGCashDonate::class, 'user_id', 'id')->orderBy('created_at', 'desc');
    }

    public function paypalDonates()
    {
        return $this->hasMany(UserPaypalDonate::class, 'user_id', 'id')->orderBy('created_at', 'desc');
    }

    public function account_details()
    {
        return $this->hasMany(AccountDetail::class, 'email', 'email');
    }
}