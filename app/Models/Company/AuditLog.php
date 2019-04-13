<?php

namespace App\Models\Company;

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
        'company_id',
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
    public function company()
    {
        return $this->belongsTo(Company::class);
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

        return '<a href="'.tenant('/employees/'.$author->id).'">'.$author->name.'</a>';
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

        return '<a href="'.tenant('/teams/'.$team->id).'">'.$team->name.'</a>';
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
            return $this->object->{'user_name'};
        }

        return '<a href="'.tenant('/employees/'.$user->id).'">'.$user->name.'</a>';
    }

    /**
     * Get the employee of the audit log, if defined.
     *
     * @return string
     */
    public function getEmployeeAttribute($value)
    {
        try {
            $employee = Employee::findOrFail($this->object->{'employee_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'employee_name'};
        }

        return '<a href="'.tenant('/employees/'.$employee->id).'">'.$employee->name.'</a>';
    }

    /**
     * Get the manager of the audit log, if defined.
     *
     * @return string
     */
    public function getManagerAttribute($value)
    {
        try {
            $manager = Employee::findOrFail($this->object->{'manager_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'manager_name'};
        }

        return '<a href="'.tenant('/employees/'.$manager->id).'">'.$manager->name.'</a>';
    }

    /**
     * Get the position of the audit log, if defined.
     *
     * @return string
     */
    public function getPositionAttribute($value)
    {
        try {
            $position = Position::findOrFail($this->object->{'position_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'position_title'};
        }

        return '<a href="'.tenant('/account/positions').'">'.$position->title.'</a>';
    }
}
