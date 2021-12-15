<?php

namespace App\Models\Company;

use Carbon\Carbon;
use App\Models\User\User;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\User\Pronoun;
use App\Helpers\StringHelper;
use App\Helpers\HolidayHelper;
use App\Helpers\BirthdayHelper;
use App\Helpers\InstanceHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    use LogsActivity,
        HasFactory;

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
        'timezone',
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
        'phone_number',
        'locked',
        'avatar_file_id',
        'holiday_balance',
        'default_dashboard_view',
        'can_manage_expenses',
        'display_welcome_message',
        'contract_renewed_at',
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
        'contract_renewed_at',
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
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the teams record associated with the employee.
     *
     * @return BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Get all the employees this employee reports to (ie the managers).
     *
     * @return HasMany
     */
    public function managers()
    {
        return $this->hasMany(DirectReport::class, 'employee_id');
    }

    /**
     * Get all the employees this employee manages.
     *
     * @return HasMany
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
     * @return BelongsTo
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
     * @return BelongsTo
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
     *
     * @return MorphMany
     */
    public function places()
    {
        return $this->morphMany('App\Models\Company\Place', 'placable');
    }

    /**
     * Get all of the employee's daily logs.
     *
     * @return HasMany
     */
    public function dailyLogs()
    {
        return $this->hasMany(EmployeeDailyCalendarEntry::class);
    }

    /**
     * Get all of the employee's planned holidays.
     *
     * @return HasMany
     */
    public function plannedHolidays()
    {
        return $this->hasMany(EmployeePlannedHoliday::class);
    }

    /**
     * Get the pronoun record associated with the employee.
     *
     * @return BelongsTo
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
     * @return BelongsToMany
     */
    public function ships()
    {
        return $this->belongsToMany(Ship::class)->orderBy('ships.created_at', 'desc');
    }

    /**
     * Get the skill records associated with the employee.
     *
     * @return BelongsToMany
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class)->orderBy('skills.name', 'asc');
    }

    /**
     * Get the expense records associated with the employee.
     *
     * @return HasMany
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the expense records approved by this employee as a manager
     * associated with the employee.
     *
     * @return HasMany
     */
    public function approvedExpenses()
    {
        return $this->hasMany(Expense::class, 'manager_approver_id', 'id');
    }

    /**
     * Get the expense records approved by this employee as someone in the
     * accounting department associated with the employee.
     *
     * @return HasMany
     */
    public function approvedAccountingExpenses()
    {
        return $this->hasMany(Expense::class, 'accounting_approver_id', 'id');
    }

    /**
     * Get the current active surveys about how his manager is doing.
     *
     * @return HasMany
     */
    public function rateYourManagerSurveys()
    {
        return $this->hasMany(RateYourManagerSurvey::class, 'manager_id');
    }

    /**
     * Get the current active surveys about how his manager is doing.
     *
     * @return HasMany
     */
    public function rateYourManagerAnswers()
    {
        return $this->hasMany(RateYourManagerAnswer::class);
    }

    /**
     * Get the one on one entries associated with the employee.
     *
     * @return HasMany
     */
    public function oneOnOneEntriesAsEmployee()
    {
        return $this->hasMany(OneOnOneEntry::class, 'employee_id');
    }

    /**
     * Get the one on one entries associated with the employee being a manager.
     *
     * @return HasMany
     */
    public function oneOnOneEntriesAsManager()
    {
        return $this->hasMany(OneOnOneEntry::class, 'manager_id');
    }

    /**
     * Get the Guess Employee Games records associated with the employee.
     *
     * @return HasMany
     */
    public function gamesAsPlayer()
    {
        return $this->hasMany(GuessEmployeeGame::class, 'employee_who_played_id');
    }

    /**
     * Get the Guess Employee Games records associated with the employee.
     *
     * @return HasMany
     */
    public function gamesAsPersonToFind()
    {
        return $this->hasMany(GuessEmployeeGame::class, 'employee_to_find_id');
    }

    /**
     * Get the Consultant Rates records associated with the employee.
     *
     * @return HasMany
     */
    public function consultantRates()
    {
        return $this->hasMany(ConsultantRate::class);
    }

    /**
     * Get the project records associated with the employee.
     *
     * @return BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps()->withPivot('role', 'created_at');
    }

    /**
     * Get the project records as lead associated with the employee.
     *
     * @return HasMany
     */
    public function projectsAsLead()
    {
        return $this->hasMany(Project::class, 'project_lead_id');
    }

    /**
     * Get the project decision records associated with the employee.
     *
     * @return HasMany
     */
    public function projectDecisions()
    {
        return $this->hasMany(ProjectDecision::class, 'author_id', 'id');
    }

    /**
     * Get the project message records associated with the employee.
     *
     * @return HasMany
     */
    public function projectMessages()
    {
        return $this->hasMany(ProjectMessage::class, 'author_id', 'id');
    }

    /**
     * Get the project task records associated with the employee.
     *
     * @return HasMany
     */
    public function projectTasksAsAuthor()
    {
        return $this->hasMany(ProjectTask::class, 'author_id', 'id');
    }

    /**
     * Get the project task records associated with the employee.
     *
     * @return HasMany
     */
    public function assigneeOfprojectTasks()
    {
        return $this->hasMany(ProjectTask::class, 'assignee_id', 'id');
    }

    /**
     * Get the timesheet records associated with the employee.
     *
     * @return HasMany
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class, 'employee_id', 'id');
    }

    /**
     * Get the timesheet records associated with the employee as approver.
     *
     * @return HasMany
     */
    public function timesheetsAsApprover()
    {
        return $this->hasMany(Timesheet::class, 'approver_id', 'id');
    }

    /**
     * Get the group records associated with the employee.
     *
     * @return BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    /**
     * Get the meeting objects the employee has participated.
     *
     * @return belongsToMany
     */
    public function meetings()
    {
        return $this->belongsToMany(Meeting::class)->withTimestamps()->withPivot('was_a_guest', 'attended');
    }

    /**
     * Get the software objects the employee.
     *
     * @return belongsToMany
     */
    public function softwares()
    {
        return $this->belongsToMany(Software::class)->withTimestamps()->withPivot('notes');
    }

    /**
     * Get the agenda item objects presented by the employee.
     *
     * @return HasMany
     */
    public function agendaItems()
    {
        return $this->hasMany(AgendaItem::class, 'presented_by_id');
    }

    /**
     * Get the file records associated with the employee as the uploader.
     *
     * @return HasMany
     */
    public function filesUploaded()
    {
        return $this->hasMany(File::class, 'uploader_employee_id', 'id');
    }

    /**
     * Get the avatar associated with the employee.
     *
     * @return HasOne
     */
    public function picture()
    {
        return $this->hasOne(File::class, 'id', 'avatar_file_id');
    }

    /**
     * Get the employee position history associated with the employee.
     *
     * @return HasMany
     */
    public function positionHistoryEntries()
    {
        return $this->hasMany(EmployeePositionHistory::class);
    }

    /**
     * Get the job openings associated with the employee as sponsor.
     *
     * @return BelongsToMany
     */
    public function jobOpeningsAsSponsor()
    {
        return $this->belongsToMany(JobOpening::class, 'job_opening_sponsor')->withTimestamps();
    }

    /**
     * Get all of the comments written by the employee.
     *
     * @return hasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id');
    }

    /**
     * Get the issues the employee is the reporter of.
     *
     * @return HasMany
     */
    public function issues()
    {
        return $this->hasMany(ProjectIssue::class, 'reporter_id');
    }

    /**
     * Get the project issues associated with the employee as assignee.
     *
     * @return BelongsToMany
     */
    public function issuesAsAssignee()
    {
        return $this->belongsToMany(ProjectIssue::class, 'project_issue_assignees');
    }

    /**
     * Get all discipline cases about the employee.
     *
     * @return HasMany
     */
    public function disciplineCases()
    {
        return $this->hasMany(DisciplineCase::class);
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
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        return [
            'id' => $this->id,
            'company' => [
                'id' => $this->company_id,
            ],
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'avatar' => ImageHelper::getAvatar($this),
            'email' => $this->email,
            'locked' => $this->locked,
            'birthdate' => (! $this->birthdate) ? null : [
                'full' => DateHelper::formatDate($this->birthdate),
                'partial' => DateHelper::formatMonthAndDay($this->birthdate),
                'year' => $this->birthdate->year,
                'month' => $this->birthdate->month,
                'day' => $this->birthdate->day,
                'age' => BirthdayHelper::age($this->birthdate, $this->timezone),
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
     * @return string|null
     */
    public function getNameAttribute($value): ?string
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
        return $this->managers()->orderBy('id')->get()->map(function ($directReport) {
            return $directReport->manager;
        });
    }

    /**
     * Get the list of direct reports of this employee.
     *
     * @return Collection
     */
    public function getListOfDirectReports(): Collection
    {
        return $this->directReports()->get()->map(function ($directReport) {
            return $directReport->directReport;
        });
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
        $yearCompletionRate = $currentDate->dayOfYear * 100 / $daysInYear;

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

    /**
     * Check wether the employee is part of the given project.
     *
     * @param int $projectId
     * @return bool
     */
    public function isInProject(int $projectId): bool
    {
        $result = DB::table('employee_project')
            ->where('employee_id', $this->id)
            ->where('project_id', $projectId)
            ->count();

        return $result == 1;
    }
}
