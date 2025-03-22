<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Character extends Model
{
    use HasFactory;
    protected $connection = 'CHARACTER_DBF_CONNECTION';

    public $table = 'CHARACTER_01_DBF.dbo.CHARACTER_TBL';

    public $primaryKey = 'm_idPlayer';

    public $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    public $timestamps = false;

  
}
