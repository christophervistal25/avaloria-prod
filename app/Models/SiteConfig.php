<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiteConfig extends Model
{
    use HasFactory;

    protected $connection = 'DR2_SITE_CONFIG';

    public $table = 'TB_Patch_Version';

    public $primaryKey = 'orderindex';

    public $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    public $timestamps = false;
}
