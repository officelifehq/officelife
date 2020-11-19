<?php

namespace App\Models\Company;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
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
        'has_dummy_data',
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
