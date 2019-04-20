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
     * @param mixed $value
     */
    public function getObjectAttribute($value)
    {
        return json_decode($this->objects);
    }

    /**
     * Get the date of the employee log.
     *
     * @return string
     * @param mixed $value
     */
    public function getDateAttribute($value): string
    {
        return DateHelper::getShortDateWithTime($this->created_at);
    }

    /**
     * Get the author of the employee log, if defined.
     *
     * @return string
     * @param mixed $value
     */
    public function getAuthorAttribute($value): string
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
     * @param mixed $value
     */
    public function getTeamAttribute($value): string
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
     * @param mixed $value
     */
    public function getUserAttribute($value): string
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
     * @param mixed $value
     */
    public function getManagerAttribute($value): string
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
     * @param mixed $value
     */
    public function getDirectReportAttribute($value): string
    {
        try {
            $employee = Employee::findOrFail($this->object->{'direct_report_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'direct_report_name'};
        }

        return '<a href="'.tenant('/employees/'.$employee->id).'">'.$employee->name.'</a>';
    }

    /**
     * Get the position of the employee log, if defined.
     *
     * @return string
     * @param mixed $value
     */
    public function getPositionAttribute($value): string
    {
        try {
            $position = Position::findOrFail($this->object->{'position_id'});
        } catch (ModelNotFoundException $e) {
            return $this->object->{'position_title'};
        }

        return $position->title;
    }
}
