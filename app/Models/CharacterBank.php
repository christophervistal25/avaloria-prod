<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterBank extends Model
{
    protected $connection = 'CHARACTER_DBF_CONNECTION';

    public $table = 'CHARACTER_01_DBF.dbo.BANK_TBL';

    public $primaryKey = 'm_idPlayer';

    public $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    public $timestamps = false;

}
