<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BattleSquare extends Model
{
    use HasFactory;
    protected $connection = 'DR2_DEF_CONNECTION';

    public $table = 'TB_DefBS_Game';

    public $primaryKey = 'f_BS_GAME_IDX';

    public $incrementing = false;

    protected $guarded = [];

    public $timestamps = false;

    public $casts = [
        'f_Ch_StartTime' => 'datetime:H:i:s',
    ];

    public $appends = [
        'map_fight',
    ];

    public function getMapFightAttribute()
    {
        $data = [
            9940102 => 'Traitors Flag Fight Area',
            9940101 => 'Windia Flag Fight Area',
            9940103 => 'Mirinae Flag Fight Area',
            9940104 => 'Swamp Flag Fight Area',
            9940105 => 'Tutu Tree Flag Fight Area',
            9940106 => 'Fire Dragon Flag Fight Area',
            9940110 => 'Water Dragon Flag Fight Area',
            9940112 => 'Fungoid Flag Fight Area',
        ];


        if(array_key_exists($this->f_GenGroupGroundNo, $data)) {
            return $data[$this->f_GenGroupGroundNo];
        }
        return "";
    }
}
