<?php

namespace App\Models\Company;

use App\Models\User\User;
use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TeamLog extends Model
{
    protected $table = 'team_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'team_id',
        'action',
        'objects',
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
     * Get the account record associated with the team log.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

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
    public function getDateAttribute($value) : string
    {
        return DateHelper::getShortDateWithTime($this->created_at);
    }

    /**
     * Get the author of the team log, if defined.
     *
     * @return string
     * @param mixed $value
     */
    public function getAuthorAttribute($value) : string
    {
        try {
            $author = User::findOrFail($this->object->{'author_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'author_name'};
        }

        return '<a href="'.tenant('/employees/'.$author->id).'">'.$author->name.'</a>';
    }

    /**
     * Get the team of the team log, if defined.
     *
     * @return string
     * @param mixed $value
     */
    public function getTeamAttribute($value) : string
    {
        try {
            $team = Team::findOrFail($this->object->{'team_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'team_name'};
        }

        return '<a href="'.tenant('/teams/'.$team->id).'">'.$team->name.'</a>';
    }

    /**
     * Get the team leader of the team log, if defined.
     *
     * @return string
     * @param mixed $value
     */
    public function getTeamLeaderAttribute($value) : string
    {
        try {
            $teamLeader = Employee::findOrFail($this->object->{'team_leader_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'team_leader_name'};
        }

        return '<a href="'.tenant('/employees/'.$teamLeader->id).'">'.$teamLeader->name.'</a>';
    }
}
