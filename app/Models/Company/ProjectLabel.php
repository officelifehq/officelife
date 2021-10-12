<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectLabel extends Model
{
    use HasFactory;

    protected $table = 'project_labels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'name',
    ];

    /**
     * Get the project record associated with the project label.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the project issue records associated with the project label.
     *
     * @return belongsToMany
     */
    public function issues()
    {
        return $this->belongsToMany(ProjectIssue::class, 'project_issue_project_label', 'project_label_id', 'project_issue_id');
    }
}
