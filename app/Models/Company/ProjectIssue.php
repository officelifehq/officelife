<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectIssue extends Model
{
    use HasFactory;

    protected $table = 'project_issues';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'project_board_id',
        'reporter_id',
        'issue_type_id',
        'is_separator',
        'id_in_project',
        'key',
        'slug',
        'title',
        'description',
        'story_points',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_separator' => 'boolean',
        'story_points' => 'integer',
    ];

    /**
     * Get the project record associated with the project issue.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the employee who created the issue.
     *
     * @return BelongsTo
     */
    public function reporter()
    {
        return $this->belongsTo(Employee::class, 'reporter_id');
    }

    /**
     * Get the issue type record associated with the project issue.
     *
     * @return BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(IssueType::class, 'issue_type_id');
    }

    /**
     * Get the project board associated with the project issue.
     *
     * @return BelongsTo
     */
    public function board()
    {
        return $this->belongsTo(ProjectBoard::class, 'project_board_id');
    }

    /**
     * Get the project sprint records associated with the project issue.
     *
     * @return belongsToMany
     */
    public function sprints()
    {
        return $this->belongsToMany(ProjectSprint::class, 'project_issue_project_sprint', 'project_issue_id', 'project_sprint_id')->withPivot('position');
    }

    /**
     * Get the project label records associated with the project issue.
     *
     * @return belongsToMany
     */
    public function labels()
    {
        return $this->belongsToMany(ProjectLabel::class, 'project_issue_project_label', 'project_issue_id', 'project_label_id');
    }

    /**
     * Get the employee records associated with the project issue, as assignees.
     *
     * @return belongsToMany
     */
    public function assignees()
    {
        return $this->belongsToMany(Employee::class, 'project_issue_assignees', 'project_issue_id', 'employee_id');
    }

    /**
     * Get the parent project issues associated with the project issue.
     *
     * @return belongsToMany
     */
    public function parents()
    {
        return $this->belongsToMany(ProjectIssue::class, 'project_issue_parents', 'child_project_issue_id', 'parent_project_issue_id');
    }

    /**
     * Get the child project issues associated with the project issue.
     *
     * @return belongsToMany
     */
    public function children()
    {
        return $this->belongsToMany(ProjectIssue::class, 'project_issue_parents', 'parent_project_issue_id', 'child_project_issue_id');
    }

    /**
     * Get all of the comments associated with the project issue.
     *
     * @return MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
