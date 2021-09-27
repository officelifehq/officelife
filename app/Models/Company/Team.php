<?php

namespace App\Models\Company;

use Carbon\Carbon;
use App\Helpers\ImageHelper;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use LogsActivity,
        HasFactory;

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
     * Get the job opening records associated with the team.
     *
     * @return HasMany
     */
    public function jobOpenings()
    {
        return $this->hasMany(JobOpening::class);
    }

    /**
     * Returns an array of work logs of all the active team members in the
     * given team on a given date.
     *
     * @param Carbon $date
     * @param Employee $loggedEmployee
     * @return Collection
     */
    public function worklogsForDate(Carbon $date, Employee $loggedEmployee): Collection
    {
        $worklogs = DB::table('employees')
            ->join('worklogs', 'employees.id', '=', 'worklogs.employee_id')
            ->join('employee_team', 'employees.id', '=', 'employee_team.employee_id')
            ->where([
                ['worklogs.created_at', 'LIKE', $date->format('Y-m-d').'%'],
                ['employee_team.team_id', '=', $this->id],
                ['employees.locked', '=', false],
            ])
            ->select('worklogs.content', 'worklogs.id as worklog_id', 'employees.id', 'employees.first_name', 'employees.email', 'employees.last_name', 'employees.avatar_file_id')
            ->get();

        $worklogCollection = collect();
        foreach ($worklogs as $worklog) {
            // gathering information about the employee
            $employee = new Employee();
            $employee->id = $worklog->id;
            $employee->email = $worklog->email;
            $employee->first_name = $worklog->first_name;
            $employee->last_name = $worklog->last_name;
            $employee->avatar_file_id = $worklog->avatar_file_id;

            // does the current logged employee has the right to edit the worklog
            $canDeleteWorklog = PermissionHelper::permissions($loggedEmployee, $employee)['can_delete_worklog'];

            $worklogCollection->push([
                'content' => $worklog->content,
                'id' => $worklog->worklog_id,
                'employee_id' => $employee->id,
                'first_name' => $employee->first_name,
                'email' => $employee->email,
                'last_name' => $employee->last_name,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 22),
                'can_delete_worklog' => $canDeleteWorklog,
            ]);
        }

        return $worklogCollection;
    }
}
