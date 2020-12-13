<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * A ProjectTask is different from a regular task. It’s very specific to a project.
 */
class ProjectTaskList extends Model
{
    use LogsActivity, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_task_lists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'author_id',
        'title',
        'description',
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
     * Get the project record associated with the task list.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the employee record associated with the task list.
     *
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }
}
