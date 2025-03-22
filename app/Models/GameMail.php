<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameMail extends Model
{
    protected $connection = 'CHARACTER_DBF_CONNECTION';

    public $table = 'CHARACTER_01_DBF.dbo.MAIL_TBL';

    public $primaryKey = 'nMail';


    public $incrementing = false;

    protected $guarded = [];

    public $timestamps = false;
}
