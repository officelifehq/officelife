<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    /**
     * Possible statuses.
     */
    const CREATED = 'created';
    const STARTED = 'started';
    const PAUSED = 'paused';
    const CANCELLED = 'cancelled';
    const CLOSED = 'closed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'project_lead_id',
        'status',
        'completed',
        'name',
        'summary',
        'code',
        'emoji',
        'description',
        'started_at',
        'planned_finished_at',
        'actually_finished_at',
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
        'started_at',
        'planned_finished_at',
        'actually_finished_at',
    ];

    /**
     * Get the company record associated with the project.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the project lead record associated with the project.
     *
     * @return BelongsTo
     */
    public function lead()
    {
        return $this->belongsTo(Employee::class, 'project_lead_id');
    }

    /**
     * Get the employee records associated with the project.
     *
     * @return BelongsToMany
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class)->withTimestamps()->withPivot('role', 'created_at');
    }

    /**
     * Get the team records associated with the project.
     *
     * @return BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    /**
     * Get the employee records associated with the project.
     *
     * @return HasMany
     */
    public function links()
    {
        return $this->hasMany(ProjectLink::class);
    }

    /**
     * Get the project status associated with the project.
     *
     * @return HasMany
     */
    public function statuses()
    {
        return $this->hasMany(ProjectStatus::class);
    }

    /**
     * Get the project decisions associated with the project.
     *
     * @return hasMany
     */
    public function decisions()
    {
        return $this->hasMany(ProjectDecision::class);
    }
}
