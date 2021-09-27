<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectBoard extends Model
{
    use HasFactory;

    protected $table = 'project_boards';

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
     * Get the project record associated with the project board.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
