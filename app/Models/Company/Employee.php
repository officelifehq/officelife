<?php

namespace App\Models\Company;

use Carbon\Carbon;
use App\Models\User\User;
use App\Traits\Searchable;
use App\Helpers\DateHelper;
use App\Helpers\HolidayHelper;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        'position_id',
        'permission_level',
        'invitation_link',
        'invitation_used_at',
        'consecutive_worklog_missed',
        'employee_status_id',
        'uuid',
        'is_dummy',
        'avatar',
        'holiday_balance',
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
     * Get the notification record associated with the employee.
     *
     * @return HasMany
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the company news record associated with the employee.
     *
     * @return HasMany
     */
    public function news()
    {
        return $this->hasMany(CompanyNews::class, 'author_id', 'id');
    }

    /**
     * Get the important date records associated with the employee.
     *
     * @return HasMany
     */
    public function importantDates()
    {
        return $this->hasMany(EmployeeImportantDate::class);
    }

    /**
     * Get the morale record associated with the employee.
     *
     * @return HasMany
     */
    public function morales()
    {
        return $this->hasMany(Morale::class);
    }

    /**
     * Get all of the employee's places.
     */
    public function places()
    {
        return $this->morphMany('App\Models\Company\Place', 'placable');
    }

    /**
     * Get all of the employee's daily logs.
     */
    public function dailyLogs()
    {
        return $this->hasMany(EmployeeDailyCalendarEntry::class);
    }

    /**
     * Get all of the employee's planned holidays.
     */
    public function plannedHolidays()
    {
        return $this->hasMany(EmployeePlannedHoliday::class);
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
     * @param mixed $value
     * @return Carbon
     */
    public function getBirthdateAttribute($value)
    {
        $importantDate = $this->importantDates()
            ->where('occasion', 'birthdate')
            ->first();

        if (is_null($importantDate)) {
            return;
        }

        return $importantDate->date;
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

    /**
     * Check if the employee has already logged his morale today.
     *
     * @return bool
     */
    public function hasAlreadyLoggedMoraleToday() : bool
    {
        $morale = Morale::where('employee_id', $this->id)
            ->whereDate('created_at', Carbon::today())
            ->get();

        return $morale->count() != 0;
    }

    /**
     * Get the current address of the employee.
     *
     * @return Place|null
     */
    public function getCurrentAddress()
    {
        try {
            $place = $this->places()->where('is_active', true)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return;
        }

        return $place;
    }

    /**
     * Get the statistics of the holidays for the current year.
     *
     * @return array
     */
    public function getHolidaysInformation() : array
    {
        $ptoPolicy = $this->company->getCurrentPTOPolicy();

        $numberOfDaysLeftToEarn = HolidayHelper::getNumberOfDaysLeftToEarn($ptoPolicy, $this);
        $holidaysEarnedEachMonth = HolidayHelper::getHolidaysEarnedEachMonth($this);

        // get the yearly completion rate
        $currentDate = Carbon::now();
        $daysInYear = DateHelper::daysInYear($currentDate);
        $yearCompletionRate = Carbon::now()->dayOfYear * 100 / $daysInYear;

        return [
            'percent_year_completion_rate' => $yearCompletionRate,
            'reverse_percent_year_completion_rate' => 100 - $yearCompletionRate,
            'amount_of_allowed_holidays' => $this->amount_of_allowed_holidays,
            'number_holidays_left_to_earn_this_year' => round($numberOfDaysLeftToEarn, 1),
            'holidays_earned_each_month' => round($holidaysEarnedEachMonth, 1),
        ];
    }
}
