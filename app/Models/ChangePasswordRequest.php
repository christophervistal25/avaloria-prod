<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangePasswordRequest extends Model
{
    use HasFactory;

    protected $connection = 'DR2_MEMBER_CONNECTION';

    public $table = 'changepw_requests';

    protected $guarded = [];

    public $timestamps = false;

}
