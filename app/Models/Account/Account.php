<?php

namespace App\Models\Account;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subdomain',
    ];

    /**
     * Get the user records associated with the account.
     *
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
