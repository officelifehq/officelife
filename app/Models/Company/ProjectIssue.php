<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'reporter_id',
        'issue_type_id',
        'id_in_project',
        'key',
        'slug',
        'title',
        'description',
    ];

    /**
     * Get the project record associated with the project issues.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the reported (employee) record associated with the project issues.
     *
     * @return BelongsTo
     */
    public function reporter()
    {
        return $this->belongsTo(Employee::class, 'reporter_id');
    }

    /**
     * Get the reported (employee) record associated with the project issues.
     *
     * @return BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(IssueType::class, 'issue_type_id');
    }
}
