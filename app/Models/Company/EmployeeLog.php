<?php

namespace App\Models\Company;

use App\Models\User\User;
use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeLog extends Model
{
    protected $table = 'employee_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'employee_id',
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
     * Get the account record associated with the employee log.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the employee record associated with the employee log.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
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
     * Get the date of the employee log.
     *
     * @return string
     */
    public function getDateAttribute($value)
    {
        return DateHelper::getShortDateWithTime($this->created_at);
    }

    /**
     * Get the author of the employee log, if defined.
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
     * Get the team of the employee log, if defined.
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
     * Get the user of the employee log, if defined.
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
     * Get the manager of the employee log, if defined.
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
     * Get the direct report object of the employee log, if defined.
     *
     * @return string
     */
    public function getDirectReportAttribute($value)
    {
        try {
            $employee = Employee::findOrFail($this->object->{'direct_report_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'direct_report_name'};
        }

        return '<a href="'.tenant('/employees/'.$employee->id).'">'.$employee->name.'</a>';
    }
}
