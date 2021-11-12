<?php

namespace App\Models\Company;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use LogsActivity,
        HasFactory;

    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'currency',
        'slug',
        'location',
        'has_dummy_data',
        'logo_file_id',
        'e_coffee_enabled',
        'work_from_home_enabled',
        'founded_at',
        'code_to_join_company',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'name',
        'currency',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'has_dummy_data' => 'boolean',
        'e_coffee_enabled' => 'boolean',
        'work_from_home_enabled' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'founded_at',
    ];

    /**
     * Get the employee records associated with the company.
     *
     * @return HasMany
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get the audit logs record associated with the company.
     *
     * @return HasMany
     */
    public function logs()
    {
        return $this->hasMany(AuditLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the team records associated with the company.
     *
     * @return HasMany
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Get the title records associated with the company.
     *
     * @return HasMany
     */
    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    /**
     * Get the flow records associated with the company.
     *
     * @return HasMany
     */
    public function flows()
    {
        return $this->hasMany(Flow::class);
    }

    /**
     * Get the employee statuses records associated with the company.
     *
     * @return HasMany
     */
    public function employeeStatuses()
    {
        return $this->hasMany(EmployeeStatus::class);
    }

    /**
     * Get the company news records associated with the company.
     *
     * @return HasMany
     */
    public function news()
    {
        return $this->hasMany(CompanyNews::class);
    }

    /**
     * Get the company PTO policy records associated with the company.
     *
     * @return HasMany
     */
    public function ptoPolicies()
    {
        return $this->hasMany(CompanyPTOPolicy::class);
    }

    /**
     * Get the question records associated with the company.
     *
     * @return HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the hardware records associated with the company.
     *
     * @return HasMany
     */
    public function hardware()
    {
        return $this->hasMany(Hardware::class);
    }

    /**
     * Get the skill records associated with the company.
     *
     * @return HasMany
     */
    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    /**
     * Get the expense records associated with the company.
     *
     * @return HasMany
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the expense categories records associated with the company.
     *
     * @return HasMany
     */
    public function expenseCategories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    /**
     * Get all the managers in the company.
     *
     * @return HasMany
     */
    public function managers()
    {
        return $this->hasMany(DirectReport::class);
    }

    /**
     * Get all the projects in the company.
     *
     * @return HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get all the timesheets in the company.
     *
     * @return HasMany
     */
    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    /**
     * Get all the consultant rates used in the company.
     *
     * @return HasMany
     */
    public function consultantRates()
    {
        return $this->hasMany(ConsultantRate::class);
    }

    /**
     * Get all the ecoffee sessions in the company.
     *
     * @return HasMany
     */
    public function eCoffees()
    {
        return $this->hasMany(ECoffee::class);
    }

    /**
     * Get all the import jobs in the company.
     *
     * @return HasMany
     */
    public function importJobs()
    {
        return $this->hasMany(ImportJob::class);
    }

    /**
     * Get all groups in the company.
     *
     * @return HasMany
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * Get all company usage history records in the company.
     *
     * @return HasMany
     */
    public function usageHistory()
    {
        return $this->hasMany(CompanyDailyUsageHistory::class);
    }

    /**
     * Get all company invoice records in the company.
     *
     * @return HasMany
     */
    public function invoices()
    {
        return $this->hasMany(CompanyInvoice::class);
    }

    /**
     * Get all softwares in the company.
     *
     * @return HasMany
     */
    public function softwares()
    {
        return $this->hasMany(Software::class);
    }

    /**
     * Get all wikis written in the company.
     *
     * @return HasMany
     */
    public function wikis()
    {
        return $this->hasMany(Wiki::class);
    }

    /**
     * Get all recruiting stage templates in the company.
     *
     * @return HasMany
     */
    public function recruitingStageTemplates()
    {
        return $this->hasMany(RecruitingStageTemplate::class);
    }

    /**
     * Get the logo associated with the company.
     *
     * @return HasOne
     */
    public function logo()
    {
        return $this->hasOne(File::class, 'id', 'logo_file_id');
    }

    /**
     * Get all job openings written in the company.
     *
     * @return HasMany
     */
    public function jobOpenings()
    {
        return $this->hasMany(JobOpening::class);
    }

    /**
     * Get all candidates in the company.
     *
     * @return HasMany
     */
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    /**
     * Get all sessions in the company.
     *
     * @return HasMany
     */
    public function askMeAnythingSessions()
    {
        return $this->hasMany(AskMeAnythingSession::class);
    }

    /**
     * Get all of the comments made in the company.
     *
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all issue types in the company.
     *
     * @return HasMany
     */
    public function issueTypes()
    {
        return $this->hasMany(IssueType::class);
    }

    /**
     * Get all discipline cases in the company.
     *
     * @return HasMany
     */
    public function disciplineCases()
    {
        return $this->hasMany(DisciplineCase::class);
    }

    /**
     * Return the PTO policy for the current year.
     *
     * @return object|null
     */
    public function getCurrentPTOPolicy(): ?object
    {
        $ptoPolicy = $this->ptoPolicies()->where('year', Carbon::now()->format('Y'))->first();

        return $ptoPolicy;
    }

    /**
     * Get the list of managers in this company.
     *
     * @return Collection
     */
    public function getListOfManagers(): Collection
    {
        $managersCollection = collect([]);

        foreach ($this->managers()->get() as $manager) {
            $managersCollection->push($manager->manager);
        }

        return $managersCollection->unique('id');
    }
}
