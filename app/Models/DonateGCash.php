<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonateGCash extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'mobile_number',
        'amount',
        'equivalent_donate_points',
        'description',
        'is_active',
        'qr_path',
        'created_by',
    ];

    public function user_who_create()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
