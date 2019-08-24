<?php

namespace App\Models\Company;

use App\Helpers\LogHelper;
use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLog extends Model
{
    protected $table = 'employee_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
    public function getDateAttribute($value) : string
    {
        return DateHelper::getShortDateWithTime($this->created_at);
    }

    /**
     * Get the content of the employee log, if defined.
     *
     * @return string
     * @param mixed $value
     */
    public function getContentAttribute($value): string
    {
        return LogHelper::processEmployeeLog($this);
    }
}
