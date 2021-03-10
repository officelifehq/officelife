<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectDecision extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_decisions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'author_id',
        'title',
        'decided_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'decided_at',
    ];

    /**
     * Get the project decision record associated with the project decision decision.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the employee record associated with the project decision.
     *
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * Get the employee records associated with the project decision.
     *
     * @return BelongsToMany
     */
    public function deciders()
    {
        return $this->belongsToMany('App\Models\Company\Employee', 'project_decision_deciders', 'project_decision_id', 'employee_id');
    }
}
