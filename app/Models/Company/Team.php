<?php

namespace App\Models\Company;

use Carbon\Carbon;
use App\Traits\Searchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use LogsActivity,
        Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'description',
        'team_leader_id',
    ];

    /**
     * The attributes that are searchable with the trait.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name',
    ];

    /**
     * The list of columns we want the Searchable trait to select.
     *
     * @var array
     */
    protected $returnFromSearch = [
        'id',
        'name',
        'company_id',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'name',
    ];

    /**
     * Get the company record associated with the team.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the employee records associated with the team.
     *
     * @return BelongsToMany
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }

    /**
     * Get the employee record associated with the team.
     *
     * @return BelongsTo
     */
    public function leader()
    {
        return $this->belongsTo(Employee::class, 'team_leader_id');
    }

    /**
     * Get the team useful link records associated with the team.
     *
     * @return HasMany
     */
    public function links()
    {
        return $this->hasMany(TeamUsefulLink::class);
    }

    /**
     * Get the team news records associated with the team.
     *
     * @return HasMany
     */
    public function news()
    {
        return $this->hasMany(TeamNews::class);
    }

    /**
     * Get the team logs records associated with the team.
     *
     * @return HasMany
     */
    public function logs()
    {
        return $this->hasMany(TeamLog::class)->orderBy('audited_at', 'desc');
    }

    /**
     * Get the ship records associated with the team.
     *
     * @return HasMany
     */
    public function ships()
    {
        return $this->hasMany(Ship::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the project records associated with the team.
     *
     * @return BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * Returns an array of worklogs for a given date.
     * This method uses a raw SQL query as I don't know how to make this kind
     * of queries with Eloquent. It’s not elegant, but it’s performant.
     *
     * @param Carbon $date
     *
     * @return array
     */
    public function worklogsForDate($date): array
    {
        $worklogs = DB::select('select worklogs.content as content, employees.id as id, employees.first_name as first_name, employees.email as email, employees.last_name, employees.avatar from employees, worklogs, employee_team where employees.id = employee_team.employee_id and employees.id = worklogs.employee_id and worklogs.created_at LIKE \''.$date->format('Y-m-d').'%\' and employee_team.team_id = '.$this->id.';');

        foreach ($worklogs as $worklog) {
            $employee = new Employee();
            $employee->id = $worklog->id;
            $employee->email = $worklog->email;
            $employee->first_name = $worklog->first_name;
            $employee->last_name = $worklog->last_name;
            $worklog->name = $employee->name;
        }

        return $worklogs;
    }
}
