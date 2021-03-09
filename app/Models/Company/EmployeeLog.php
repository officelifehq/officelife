<?php

namespace App\Models\Company;

use App\Helpers\LogHelper;
use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Audit log at the employee level.
 */
class EmployeeLog extends Model
{
    use HasFactory;

    protected $table = 'employee_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'author_id',
        'author_name',
        'action',
        'objects',
        'audited_at',
        'ip_address',
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
     * Get the employee record associated with the employee log.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the author record associated with the employee log.
     *
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the JSON object.
     *
     * @param mixed $value
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
     * @param mixed $value
     *
     * @return string
     */
    public function getDateAttribute($value): string
    {
        return DateHelper::formatShortDateWithTime($this->audited_at);
    }

    /**
     * Get the content of the employee log, if defined.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function getContentAttribute($value): string
    {
        return LogHelper::processEmployeeLog($this);
    }
}
