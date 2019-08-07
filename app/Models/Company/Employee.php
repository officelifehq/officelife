<?php

namespace App\Models\Company;

use Carbon\Carbon;
use App\Models\User\User;
use App\Traits\Searchable;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    use LogsActivity,
        Searchable;

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
        'position_id',
        'permission_level',
        'invitation_link',
        'invitation_used_at',
        'consecutive_worklog_missed',
        'employee_status_id',
        'uuid',
        'is_dummy',
        'avatar',
    ];

    /**
     * The attributes that are searchable with the trait.
     *
     * @var array
     */
    protected $searchableColumns = [
        'first_name',
        'last_name',
        'email',
    ];

    /**
     * The list of columns we want the Searchable trait to select.
     *
     * @var array
     */
    protected $returnFromSearch = [
        'id',
        'first_name',
        'last_name',
        'email',
        'company_id',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'permission_level',
        'position_id',
        'employee_status_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permission_level' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'birthdate',
        'hired_at',
        'invitation_used_at',
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
     * Get all the employees this employee reports to.
     *
     * @return hasMany
     */
    public function reportsTo()
    {
        return $this->hasMany(DirectReport::class, 'employee_id');
    }

    /**
     * Get all the employees this employee manages.
     *
     * @return hasMany
     */
    public function managerOf()
    {
        return $this->hasMany(DirectReport::class, 'manager_id');
    }

    /**
     * Get the employee logs record associated with the employee.
     *
     * @return HasMany
     */
    public function employeeLogs()
    {
        return $this->hasMany(EmployeeLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the position record associated with the employee.
     *
     * @return belongsTo
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the employee events record associated with the employee.
     *
     * @return HasMany
     */
    public function employeeEvents()
    {
        return $this->hasMany(EmployeeEvent::class);
    }

    /**
     * Get the tasks record associated with the employee.
     *
     * @return HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    /**
     * Get the worklog record associated with the employee.
     *
     * @return HasMany
     */
    public function worklogs()
    {
        return $this->hasMany(Worklog::class);
    }

    /**
     * Get the employee status associated with the employee.
     *
     * @return belongsTo
     */
    public function status()
    {
        return $this->belongsTo(EmployeeStatus::class, 'employee_status_id');
    }

    /**
     * Get the permission level of the employee.
     *
     * @return string
     */
    public function getPermissionLevel() : string
    {
        return trans('app.permission_'.$this->permission_level);
    }

    /**
     * Returns the email attribute of the employee.
     *
     * @return string
     * @param mixed $value
     */
    public function getEmailAttribute($value)
    {
        return $value;
    }

    /**
     * Returns the birthdate attribute of the employee.
     *
     * @return string
     * @param mixed $value
     */
    public function getBirthdateAttribute($value)
    {
        return $value;
    }

    /**
     * Returns the name attribute of the employee.
     *
     * @return string
     * @param mixed $value
     */
    public function getNameAttribute($value) : string
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
     * Get the list of managers of this employee.
     *
     * @return Collection
     */
    public function getListOfManagers() : Collection
    {
        $managersCollection = collect([]);
        foreach ($this->reportsTo()->get() as $directReport) {
            $managersCollection->push($directReport->manager);
        }

        return $managersCollection;
    }

    /**
     * Get the list of direct reports of this employee.
     *
     * @return Collection
     */
    public function getListOfDirectReports() : Collection
    {
        $directReportCollection = collect([]);
        foreach ($this->managerOf()->get() as $directReport) {
            $directReportCollection->push($directReport->directReport);
        }

        return $directReportCollection;
    }

    /**
     * Get the fully qualified path to confirm account invitation.
     *
     * @return string
     */
    public function getPathInvitationLink() : string
    {
        return secure_url('/invite/employee/'.$this->invitation_link);
    }

    /**
     * Return true if the invitation to become a user has already been accepted.
     *
     * @return bool
     */
    public function invitationAlreadyAccepted() : bool
    {
        if ($this->invitation_used_at) {
            return true;
        }

        return false;
    }

    /**
     * Check if the employee has already logged something today.
     *
     * @return bool
     */
    public function hasAlreadyLoggedWorklogToday() : bool
    {
        $worklog = Worklog::where('employee_id', $this->id)
            ->whereDate('created_at', Carbon::today())
            ->get();

        return $worklog->count() != 0;
    }
}
