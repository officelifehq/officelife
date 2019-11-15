<?php

namespace App\Models\Company;

use App\Helpers\LogHelper;
use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamLog extends Model
{
    protected $table = 'team_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id',
        'action',
        'objects',
        'author_id',
        'author_name',
        'audited_at',
        'ip_address',
        'is_dummy',
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'audited_at',
    ];

    /**
     * Get the team record associated with the team log.
     *
     * @return BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the JSON object.
     *
     * @return array
     * @param mixed $value
     */
    public function getObjectAttribute($value)
    {
        return json_decode($this->objects);
    }

    /**
     * Get the date of the team log.
     *
     * @return string
     * @param mixed $value
     */
    public function getDateAttribute($value): string
    {
        return DateHelper::getShortDateWithTime($this->created_at);
    }

    /**
     * Get the content of the team log, if defined.
     *
     * @return string
     * @param mixed $value
     */
    public function getContentAttribute($value): string
    {
        return LogHelper::processTeamLog($this);
    }
}
