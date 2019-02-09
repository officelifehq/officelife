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
        'identities',
        'permission_level',
        'uuid',
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
     * Get the JSON object.
     *
     * @return array
     */
    public function getIdentityAttribute($value)
    {
        return json_decode($this->identities);
    }

    /**
     * Returns the email attribute of the employee.
     *
     * @return string
     */
    public function getEmailAttribute($value) : String
    {
        return $this->identity->{'email'};
    }

    /**
     * Returns the name attribute of the employee.
     *
     * @return string
     */
    public function getNameAttribute($value) : String
    {
        if (! $this->identity->{'first_name'}) {
            return $this->email;
        }

        $completeName = $this->identity->{'first_name'};

        if (! is_null($this->identity->{'last_name'})) {
            $completeName = $completeName.' '.$this->identity->{'last_name'};
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
