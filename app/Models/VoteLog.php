<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteLog extends Model
{
    use HasFactory;

    protected $table = 'vote_logs';

    protected $fillable = [
        'account',
        'ip_address',
        'voted_at',
    ];

    public $timestamps = true;
}