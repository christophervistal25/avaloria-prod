<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogTransaction extends Model
{
    use HasFactory;

    protected $connection = 'DR2_MEMBER_CONNECTION';

    public $table = 'log_transactions';

    protected $fillable = [
        'token',
        'payer',
        'timestamp',
        'account_id',
        'account_login',
        'pack_id',
        'pack_name',
        'pack_quantity',
        'pack_price',
        'transaction_type',
        'status',
        'cash',
    ];

    public $timestamps = false;

    public $appends = [
        'date_format',
    ];

    public function getDateFormatAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['timestamp']);
    }

    public function account_purchase()
    {
        return $this->belongsTo(User::class, 'account_login', 'ID');
    }
}
