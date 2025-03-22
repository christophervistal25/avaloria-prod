<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use HasFactory;

    protected $connection = 'DR2_MEMBER_CONNECTION';

    public $table = 'Member';

    public $primaryKey = 'MemberKey';

    public $timestamps = false;

    public $incrementing = false;

    protected $guarded = [];
}
