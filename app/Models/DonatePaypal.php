<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonatePaypal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'equivalent_donate_points',
        'description',
        'is_active',
        'created_by',
    ];

    public function user_who_create()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
