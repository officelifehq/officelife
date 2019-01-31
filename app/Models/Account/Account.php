<?php

namespace App\Models\Account;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subdomain',
        'confirmed',
        'has_dummy_data',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'subdomain',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'confirmed' => 'boolean',
        'has_dummy_data' => 'boolean',
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

    /**
     * Get the teams record associated with the account.
     *
     * @return HasMany
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get the audit logs record associated with the account.
     *
     * @return HasMany
     */
    public function logs()
    {
        return $this->hasMany(AuditLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the fully qualified path to registration.
     *
     * @return string
     */
    public function getPathConfirmationLink()
    {
        return secure_url('register/confirm', $this->confirmation_link);
    }
}
