<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeTrackingEntry extends Model
{
    use LogsActivity,
        HasFactory;

    protected $table = 'time_tracking_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timesheet_id',
        'project_id',
        'project_task_id',
        'employee_id',
        'duration',
        'happened_at',
        'description',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'duration',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'happened_at',
    ];

    /**
     * Get the timesheet record associated with the time tracking entry.
     *
     * @return BelongsTo
     */
    public function timesheet()
    {
        return $this->belongsTo(Timesheet::class);
    }

    /**
     * Get the employee record associated with the time tracking entry.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the project record associated with the time tracking entry.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the project task record associated with the time tracking entry.
     *
     * @return BelongsTo
     */
    public function projectTask()
    {
        return $this->belongsTo(ProjectTask::class);
    }
}
