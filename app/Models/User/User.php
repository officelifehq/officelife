<?php

namespace App\Models\User;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'email',
        'password',
        'first_name',
        'last_name',
        'middle_name',
        'nickname',
        'uuid',
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
    ];

    /**
     * Get the employee records associated with the user.
     *
     * @return hasMany
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get the notification record associated with the user.
     *
     * @return hasMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the name of the user.
     *
     * @param string $value
     * @return string
     */
    public function getNameAttribute($value): String
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
     * Get the fully qualified path to registration.
     *
     * @return string
     */
    public function getPathConfirmationLink(): string
    {
        return secure_url('register/confirm/'.$this->verification_link);
    }

    /**
     * Check if the user is part of the given company.
     *
     * @param Company $company
     * @return Employee|null
     */
    public function getEmployeeObjectForCompany(Company $company)
    {
        $employee = Employee::where('user_id', $this->id)
            ->where('company_id', $company->id)
            ->first();

        if ($employee) {
            return $employee;
        }
    }
}
