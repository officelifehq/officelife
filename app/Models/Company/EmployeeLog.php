<?php

namespace App\Models\Company;

use App\Helpers\LogHelper;
use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Audit log at the employee level.
 */
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
        'author_id',
        'author_name',
        'action',
        'objects',
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
     * Transform the object to an array representing this object.
     *
     * @return array
     */
    public function toObject(): array
    {
        return [
            'id' => $this->id,
            'action' => $this->action,
            'objects' => json_decode($this->objects),
            'localized_content' => $this->content,
            'author' => [
                'id' => is_null($this->author) ? null : $this->author->id,
                'name' => is_null($this->author) ? $this->author_name : $this->author->name,
            ],
            'localized_audited_at' => $this->date,
            'audited_at' => $this->audited_at,
        ];
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
        return DateHelper::formatShortDateWithTime($this->audited_at);
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
