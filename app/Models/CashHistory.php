<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashHistory extends Model
{
    use HasFactory;

    protected $connection = 'ACCOUNT_DBF';

    public $table = 'CashHistory';

    public $primaryKey = 'account';

    public $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    public $timestamps = false;
}
