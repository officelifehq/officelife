<?php

namespace App\Models\Company;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    use LogsActivity,
        Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'team_leader_id',
        'is_dummy',
    ];

    /**
     * The attributes that are searchable with the trait.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name',
    ];

    /**
     * The list of columns we want the Searchable trait to select.
     *
     * @var array
     */
    protected $returnFromSearch = [
        'id',
        'name',
        'company_id',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_dummy' => 'boolean',
    ];

    /**
     * Get the company record associated with the team.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the employee records associated with the team.
     *
     * @return belongsToMany
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }

    /**
     * Get the employee record associated with the team.
     *
     * @return BelongsTo
     */
    public function leader()
    {
        return $this->belongsTo(Employee::class, 'team_leader_id');
    }

    /**
     * Get the task records associated with the team.
     *
     * @return hasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
