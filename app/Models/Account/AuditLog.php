<?php

namespace App\Models\Account;

use App\Models\User\User;
use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
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
     * Get the account record associated with the audit log.
     *
     * @return BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the JSON object.
     *
     * @return array
     */
    public function getObjectAttribute($value)
    {
        return json_decode($this->objects);
    }

    /**
     * Get the date of the audit log.
     *
     * @return string
     */
    public function getDateAttribute($value)
    {
        return DateHelper::getShortDateWithTime($this->created_at);
    }

    /**
     * Get the author of the audit log, if defined.
     *
     * @return string
     */
    public function getAuthorAttribute($value)
    {
        try {
            $author = User::findOrFail($this->object->{'author_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'author_name'};
        }

        return '<a href="/users/'.$author->id.'">'.$author->name.'</a>';
    }

    /**
     * Get the team of the audit log, if defined.
     *
     * @return string
     */
    public function getTeamAttribute($value)
    {
        try {
            $team = Team::findOrFail($this->object->{'team_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'team_name'};
        }

        return '<a href="/teams/'.$team->id.'">'.$team->name.'</a>';
    }

    /**
     * Get the user of the audit log, if defined.
     *
     * @return string
     */
    public function getUserAttribute($value)
    {
        try {
            $user = User::findOrFail($this->object->{'user_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'user_email'};
        }

        return '<a href="/users/'.$user->id.'">'.$user->name.'</a>';
    }
}
