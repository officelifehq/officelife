<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMessage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'author_id',
        'title',
        'content',
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
}
