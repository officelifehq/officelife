<?php

namespace App\Models\Company;

use Carbon\Carbon;
use App\Models\User\User;
use App\Traits\Searchable;
use App\Helpers\DateHelper;
use App\Models\User\Pronoun;
use App\Helpers\StringHelper;
use App\Helpers\HolidayHelper;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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
        'birthdate',
        'hired_at',
        'description',
        'twitter_handle',
        'slack_handle',
        'position_id',
        'permission_level',
        'invitation_link',
        'invitation_used_at',
        'consecutive_worklog_missed',
        'employee_status_id',
        'uuid',
        'locked',
        'avatar',
        'holiday_balance',
        'default_dashboard_view',
        'can_manage_expenses',
        'display_welcome_message',
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
        'avatar',
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
        'locked' => 'boolean',
        'can_manage_expenses' => 'boolean',
        'display_welcome_message' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'invitation_used_at',
        'hired_at',
        'birthdate',
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
     * Get the teams record associated with the employee.
     *
     * @return belongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Get all the employees this employee reports to (ie the managers).
     *
     * @return hasMany
     */
    public function managers()
    {
        return $this->hasMany(DirectReport::class, 'employee_id');
    }

    /**
     * Get all the employees this employee manages.
     *
     * @return hasMany
     */
    public function directReports()
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
        return $this->hasMany(EmployeeLog::class)->orderBy('audited_at', 'desc');
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
     * Get the tasks record associated with the employee.
     *
     * @return HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
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
     * Get the pronoun record associated with the employee.
     *
     * @return belongsTo
     */
    public function pronoun()
    {
        return $this->belongsTo(Pronoun::class);
    }

    /**
     * Get the team news records associated with the employee.
     *
     * @return HasMany
     */
    public function teamNews()
    {
        return $this->hasMany(TeamNews::class, 'author_id', 'id');
    }

    /**
     * Get the work from home records associated with the employee.
     *
     * @return HasMany
     */
    public function workFromHomes()
    {
        return $this->hasMany(WorkFromHome::class);
    }

    /**
     * Get the answer records associated with the employee.
     *
     * @return HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the hardware records associated with the employee.
     *
     * @return HasMany
     */
    public function hardware()
    {
        return $this->hasMany(Hardware::class);
    }

    /**
     * Get the ship records associated with the employee.
     *
     * @return belongsToMany
     */
    public function ships()
    {
        return $this->belongsToMany(Ship::class)->orderBy('ships.created_at', 'desc');
    }

    /**
     * Get the skill records associated with the employee.
     *
     * @return belongsToMany
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class)->orderBy('skills.name', 'asc');
    }

    /**
     * Get the expense records associated with the employee.
     *
     * @return hasMany
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the expense records approved by this employee as a manager
     * associated with the employee.
     *
     * @return hasMany
     */
    public function approvedExpenses()
    {
        return $this->hasMany(Expense::class, 'manager_approver_id', 'id');
    }

    /**
     * Get the expense records approved by this employee as someone in the
     * accounting department associated with the employee.
     *
     * @return hasMany
     */
    public function approvedAccountingExpenses()
    {
        return $this->hasMany(Expense::class, 'accounting_approver_id', 'id');
    }

    /**
     * Get the current active surveys about how his manager is doing.
     *
     * @return hasMany
     */
    public function rateYourManagerSurveys()
    {
        return $this->hasMany(RateYourManagerSurvey::class, 'manager_id');
    }

    /**
     * Get the current active surveys about how his manager is doing.
     *
     * @return hasMany
     */
    public function rateYourManagerAnswers()
    {
        return $this->hasMany(RateYourManagerAnswer::class);
    }

    /**
     * Get the one on one entries associated with the employee.
     *
     * @return hasMany
     */
    public function oneOnOneEntriesAsEmployee()
    {
        return $this->hasMany(OneOnOneEntry::class, 'employee_id');
    }

    /**
     * Get the one on one entries associated with the employee being a manager.
     *
     * @return hasMany
     */
    public function oneOnOneEntriesAsManager()
    {
        return $this->hasMany(OneOnOneEntry::class, 'manager_id');
    }

    /**
     * Get the Guess Employee Games records associated with the employee.
     *
     * @return hasMany
     */
    public function gamesAsPlayer()
    {
        return $this->hasMany(GuessEmployeeGame::class, 'employee_who_played_id');
    }

    /**
     * Get the Guess Employee Games records associated with the employee.
     *
     * @return hasMany
     */
    public function gamesAsPersonToFind()
    {
        return $this->hasMany(GuessEmployeeGame::class, 'employee_to_find_id');
    }

    /**
     * Scope a query to only include unlocked users.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeNotLocked(Builder $query): Builder
    {
        return $query->where('locked', false);
    }

    /**
     * Transform the object to an array representing this object.
     *
     * @return array
     */
    public function toObject(): array
    {
        $address = $this->getCurrentAddress();

        return [
            'id' => $this->id,
            'company' => [
                'id' => $this->company_id,
            ],
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'avatar' => $this->avatar,
            'email' => $this->email,
            'locked' => $this->locked,
            'birthdate' => (! $this->birthdate) ? null : [
                'full' => DateHelper::formatDate($this->birthdate),
                'partial' => DateHelper::formatMonthAndDay($this->birthdate),
                'year' => $this->birthdate->year,
                'month' => $this->birthdate->month,
                'day' => $this->birthdate->day,
                'age' => Carbon::now()->year - $this->birthdate->year,
            ],
            'raw_description' => $this->description,
            'parsed_description' => is_null($this->description) ? null : StringHelper::parse($this->description),
            'permission_level' => $this->getPermissionLevel(),
            'address' => is_null($address) ? null : $address->toObject(),
            'position' => (! $this->position) ? null : [
                'id' => $this->position->id,
                'title' => $this->position->title,
            ],
            'pronoun' => (! $this->pronoun) ? null : [
                'id' => $this->pronoun->id,
                'label' => $this->pronoun->label,
            ],
            'user' => (! $this->user) ? null : [
                'id' => $this->user->id,
            ],
            'status' => (! $this->status) ? null : [
                'id' => $this->status->id,
                'name' => $this->status->name,
            ],
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Get the permission level of the employee.
     *
     * @return string
     */
    public function getPermissionLevel(): string
    {
        return trans('app.permission_'.$this->permission_level);
    }

    /**
     * Returns the email attribute of the employee.
     *
     *
     * @param mixed $value
     *
     * @return string
     */
    public function getEmailAttribute($value)
    {
        return $value;
    }

    /**
     * Returns the name attribute of the employee.
     *
     *
     * @param mixed $value
     *
     * @return string
     */
    public function getNameAttribute($value): string
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
    public function getListOfManagers(): Collection
    {
        $managersCollection = collect([]);
        foreach ($this->managers()->get() as $directReport) {
            $managersCollection->push($directReport->manager);
        }

        return $managersCollection;
    }

    /**
     * Get the list of direct reports of this employee.
     *
     * @return Collection
     */
    public function getListOfDirectReports(): Collection
    {
        $directReportCollection = collect([]);
        foreach ($this->directReports()->get() as $directReport) {
            $directReportCollection->push($directReport->directReport);
        }

        return $directReportCollection;
    }

    /**
     * Get the fully qualified path to confirm account invitation.
     *
     * @return string
     */
    public function getPathInvitationLink(): string
    {
        return secure_url('/invite/employee/'.$this->invitation_link);
    }

    /**
     * Check if the employee has already logged something today.
     *
     * @return bool
     */
    public function hasAlreadyLoggedWorklogToday(): bool
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
    public function hasAlreadyLoggedMoraleToday(): bool
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
    public function getCurrentAddress(): ?Place
    {
        try {
            $place = $this->places()->where('is_active', true)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $place = null;
        }

        return $place;
    }

    /**
     * Get the statistics of the holidays for the current year.
     *
     * @return array
     */
    public function getHolidaysInformation(): array
    {
        $ptoPolicy = $this->company->getCurrentPTOPolicy();

        $numberOfDaysLeftToEarn = HolidayHelper::getNumberOfDaysLeftToEarn($ptoPolicy, $this);
        $holidaysEarnedEachMonth = HolidayHelper::getHolidaysEarnedEachMonth($this);

        // get the yearly completion rate
        $currentDate = Carbon::now();
        $daysInYear = DateHelper::getNumberOfDaysInYear($currentDate);
        $yearCompletionRate = Carbon::now()->dayOfYear * 100 / $daysInYear;

        return [
            'current_balance_round' => round($this->holiday_balance, 0, PHP_ROUND_HALF_DOWN),
            'percent_year_completion_rate' => $yearCompletionRate,
            'reverse_percent_year_completion_rate' => 100 - $yearCompletionRate,
            'amount_of_allowed_holidays' => $this->amount_of_allowed_holidays,
            'number_holidays_left_to_earn_this_year' => round($numberOfDaysLeftToEarn, 1),
            'holidays_earned_each_month' => round($holidaysEarnedEachMonth, 1),
        ];
    }

    /**
     * Check wether the employee is part of the given team.
     *
     * @param int $teamId
     *
     * @return bool
     */
    public function isInTeam(int $teamId): bool
    {
        $teams = $this->teams;

        $result = $teams->filter(function ($singleTeam) use ($teamId) {
            return $singleTeam->id === $teamId;
        });

        return $result->count() == 1;
    }

    /**
     * Check wether the current employee is the manager of the given employee.
     *
     * @param int $employeeId
     * @return bool
     */
    public function isManagerOf(int $employeeId): bool
    {
        $directReports = $this->getListOfDirectReports();
        $result = $directReports->filter(function ($directReport) use ($employeeId) {
            return $directReport->id === $employeeId;
        });

        return $result->count() == 1;
    }
}
