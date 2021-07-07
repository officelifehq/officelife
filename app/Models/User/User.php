<?php

namespace App\Models\User;

use App\Jobs\SendVerifyEmail;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Translation\HasLocalePreference;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use Notifiable, LogsActivity, HasFactory, HasApiTokens, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'middle_name',
        'nickname',
        'uuid',
        'show_help',
        'locale',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'email',
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
        'email_verified_at' => 'datetime',
        'show_help' => 'boolean',
    ];

    /**
     * Get the employee records associated with the user.
     *
     * @return HasMany
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get the user tokens for external login providers.
     *
     * @return HasMany
     */
    public function tokens()
    {
        return $this->hasMany(UserToken::class);
    }

    /**
     * Get the name of the user.
     *
     * @param string $value
     *
     * @return string
     */
    public function getNameAttribute($value): string
    {
        if (is_null($this->first_name)) {
            return $this->email;
        }

        $completeName = $this->first_name;

        if (! is_null($this->last_name)) {
            $completeName = $completeName.' '.$this->last_name;
        }

        return $completeName;
    }

    /**
     * Check if the user is part of the given company.
     *
     * @param Company $company
     *
     * @return Employee|null
     */
    public function getEmployeeObjectForCompany(Company $company): ?Employee
    {
        $employee = Employee::where('user_id', $this->id)
            ->where('company_id', $company->id)
            ->first();

        if ($employee) {
            return $employee;
        }

        return null;
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        if (config('mail.verify') && self::count() > 1) {
            SendVerifyEmail::dispatch($this);
        }
    }

    /**
     * Get the preferred locale of the entity.
     *
     * @return string|null
     */
    public function preferredLocale()
    {
        return $this->locale;
    }
}
