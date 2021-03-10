<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectStatus extends Model
{
    use HasFactory;

    protected $table = 'project_statuses';

    /**
     * Possible statuses.
     */
    const ON_TRACK = 'on_track';
    const LATE = 'late';
    const AT_RISK = 'at_risk';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'author_id',
        'status',
        'title',
        'description',
    ];

    /**
     * Get the project record associated with the project status.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the employee record associated with the project status.
     *
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }
}
