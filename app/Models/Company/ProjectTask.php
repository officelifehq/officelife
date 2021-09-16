<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * A ProjectTask is different from a regular task. It’s very specific to a project.
 */
class ProjectTask extends Model
{
    use LogsActivity,
        HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'project_task_list_id',
        'author_id',
        'assignee_id',
        'title',
        'description',
        'completed',
        'completed_at',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'title',
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'completed' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'completed_at',
    ];

    /**
     * Get the project record associated with the task.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the author record associated with the task.
     *
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * Get the project task list record associated with the task.
     *
     * @return BelongsTo
     */
    public function list()
    {
        return $this->belongsTo(ProjectTaskList::class, 'project_task_list_id');
    }

    /**
     * Get the assignee record associated with the task.
     *
     * @return BelongsTo
     */
    public function assignee()
    {
        return $this->belongsTo(Employee::class, 'assignee_id');
    }

    /**
     * Get the time tracking record associated with the task.
     *
     * @return hasMany
     */
    public function timeTrackingEntries()
    {
        return $this->hasMany(TimeTrackingEntry::class);
    }

    /**
     * Get all of the comments associated with the project message.
     *
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
