<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamNews extends Model
{
    use LogsActivity;

    protected $table = 'team_news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id',
        'author_id',
        'author_name',
        'title',
        'content',
        'is_dummy',
        'created_at',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'title',
        'content',
    ];

    /**
     * Get the team record associated with the team news.
     *
     * @return belongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the employee record associated with the team news.
     */
    public function author()
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }
}
