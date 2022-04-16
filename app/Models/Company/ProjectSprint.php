<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectSprint extends Model
{
    use HasFactory;

    protected $table = 'project_sprints';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'project_board_id',
        'position',
        'name',
        'active',
        'started_at',
        'completed_at',
        'is_board_backlog',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'is_board_backlog' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'started_at',
        'completed_at',
    ];

    /**
     * Get the project record associated with the project sprint.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the project board record associated with the project sprint.
     *
     * @return BelongsTo
     */
    public function board()
    {
        return $this->belongsTo(ProjectBoard::class, 'project_board_id');
    }

    /**
     * Get the project issue records associated with the project sprint.
     *
     * @return belongsToMany
     */
    public function issues()
    {
        return $this->belongsToMany(ProjectIssue::class, 'project_issue_project_sprint', 'project_sprint_id', 'project_issue_id')->withPivot('position');
    }
}
