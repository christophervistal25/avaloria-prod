<?php

namespace App\Models;

use App\Enums\CharacterClass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CharacterDetail extends Model
{
    use HasFactory;
    protected $connection = 'DR2_USER_CONNECTION';

    public $table = 'TB_CharacterSub';

    public $primaryKey = 'CharacterID';

    public $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    public $timestamps = false;

    public $casts = [
        'Class' => CharacterClass::class,
    ];

}
