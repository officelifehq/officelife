<?php

namespace App\Models\Company;

use App\Models\User\User;
use App\Mail\Company\InviteUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    use LogsActivity;

    protected $table = 'employees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'user_id',
        'email',
        'first_name',
        'last_name',
        'birthdate',
        'hired_at',
        'permission_level',
        'uuid',
        'is_dummy',
        'avatar',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'permission_level',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'birthdate',
        'hired_at',
    ];

    /**
     * Get the user record associated with the company.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Company record associated with the company.
     *
     * @return belongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the teams record associated with the user.
     *
     * @return belongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Get the permission level of the user.
     *
     * @return string
     */
    public function getPermissionLevel() : String
    {
        return trans('app.permission_'.$this->permission_level);
    }

    /**
     * Returns the email attribute of the employee.
     *
     * @return string
     */
    public function getEmailAttribute($value)
    {
        return $value;
    }

    /**
     * Returns the birthdate attribute of the employee.
     *
     * @return string
     */
    public function getBirthdateAttribute($value)
    {
        return $value;
    }

    /**
     * Returns the name attribute of the employee.
     *
     * @return string
     */
    public function getNameAttribute($value) : String
    {
        if (! $this->first_name) {
            return $this->email;
        }

        $completeName = $this->first_name;

        if (! is_null($this->last_name)) {
            $completeName = $completeName.' '.$this->last_name;
        }

        return $completeName;
    }

    /**
     * Send an email to the employee so (s)he can create a User account and
     * sign in to manage his/her account.
     *
     * @return void
     */
    public function invite()
    {
        Mail::to($this->email)
            ->queue(new InviteUser($this));
    }
}
