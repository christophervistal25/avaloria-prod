<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RedeemCode extends Model
{
    protected $fillable = [
        'code',
        'description',
        'status',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function __construct()
    {
        $this->table = DB::connection($this->connection)->getDatabaseName().'.dbo.'.$this->getTable();
    }

    public function details(): HasMany
    {
        return $this->hasMany(RedeemCodeDetail::class);
    }
    
    public function history(): HasOne
    {
        return $this->hasOne(RedeemHistory::class, 'code', 'code');
    }
}