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

    /**
     * Get the project sprint records associated with the project board.
     */
    public function sprints()
    {
        return $this->hasMany(ProjectSprint::class)->where('is_board_backlog', false);
    }

    /**
     * Get the active project sprint records associated with the project board.
     */
    public function activeSprints()
    {
        return $this->hasMany(ProjectSprint::class)
            ->where('is_board_backlog', false)
            ->where('completed_at', null);
    }

    /**
     * Get the project sprint record associated with the project board.
     * A backlog is basically the project sprint that was designed as the
     * backlog of the board.
     */
    public function backlog()
    {
        return $this->hasOne(ProjectSprint::class)->where('is_board_backlog', true);
    }
}
