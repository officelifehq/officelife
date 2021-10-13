<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

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
        'short_code',
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

    /**
     * Get the project messages associated with the project.
     *
     * @return HasMany
     */
    public function messages()
    {
        return $this->hasMany(ProjectMessage::class);
    }

    /**
     * Get the project tasks associated with the project.
     *
     * @return HasMany
     */
    public function tasks()
    {
        return $this->hasMany(ProjectTask::class);
    }

    /**
     * Get the project task lists associated with the project.
     *
     * @return HasMany
     */
    public function lists()
    {
        return $this->hasMany(ProjectTaskList::class);
    }

    /**
     * Get the time tracking entries associated with the project.
     *
     * @return HasMany
     */
    public function timeTrackingEntries()
    {
        return $this->hasMany(TimeTrackingEntry::class);
    }

    /**
     * Get the file entries associated with the project.
     *
     * @return BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class);
    }

    /**
     * Get the board entries associated with the project.
     *
     * @return HasMany
     */
    public function boards()
    {
        return $this->hasMany(ProjectBoard::class);
    }

    /**
     * Get the sprint entries associated with the project.
     *
     * @return HasMany
     */
    public function sprints()
    {
        return $this->hasMany(ProjectSprint::class);
    }

    /**
     * Get the issue entries associated with the project.
     *
     * @return HasMany
     */
    public function issues()
    {
        return $this->hasMany(ProjectIssue::class);
    }

    /**
     * Get the label entries associated with the project.
     *
     * @return HasMany
     */
    public function labels()
    {
        return $this->hasMany(ProjectLabel::class);
    }
}
