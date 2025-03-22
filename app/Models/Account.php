<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Cashier\Billable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use Billable;
    use HasFactory;
    use Notifiable;

    protected $connection = 'ACCOUNT_DBF';

    public $table = 'ACCOUNT_TBL';

    public $primaryKey = 'account';

    public $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'account',
        'password',
        'cash',
        'votepoint',
    ];

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'PW',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }
    
    public function __construct()
    {
        $this->table = DB::connection($this->connection)->getDatabaseName().'.dbo.'.$this->getTable();
    }

    public function account_detail()
    {
        return $this->hasOne(AccountDetail::class, 'account');
    }

    public function no_of_characters()
    {
        return $this->hasMany(Character::class, 'account', 'account')->select(['account', 'm_idPlayer']);
    }

    public function characters()
    {
        return $this->hasMany(Character::class, 'account', 'account')->select([
            'm_idPlayer',
            'account',
            'm_szName',
            'm_nJob',
            'm_nLevel',
            'm_nSTR',
            'm_nDEX',
            'm_nINT',
            'm_nLevel',
            'm_tGuildMember',
            'TotalPlayTime',
            'CreateTime',
        ]);
    }


    public function purchases()
    {
        return $this->hasMany(CashHistory::class, 'account', 'account')->orderBy('regdate', 'DESC');
    }
}
