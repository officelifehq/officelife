<?php

namespace App\Models\User;

use App\Models\Account\Team;
use App\Models\Account\Account;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, LogsActivity, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'email',
        'password',
        'permission_level',
        'first_name',
        'last_name',
        'middle_name',
        'nickname',
        'is_dummy',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'email',
        'permission_level',
        'first_name',
        'last_name',
        'middle_name',
        'nickname',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_administrator' => 'boolean',
        'is_dummy' => 'boolean',
    ];

    /**
     * Get the account record associated with the user.
     *
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the teams record associated with the user.
     *
     * @return BelongsTo
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }
}
