<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuildBank extends Model
{
    protected $connection = 'CHARACTER_DBF_CONNECTION';

    public $table = 'CHARACTER_01_DBF.dbo.GUILD_BANK_TBL';

    public $primaryKey = 'm_idGuild';

    public $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    public $timestamps = false;

}
