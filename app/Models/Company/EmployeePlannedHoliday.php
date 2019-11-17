<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePlannedHoliday extends Model
{
    protected $table = 'employee_planned_holidays';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'planned_date',
        'type',
        'full',
        'actually_taken',
        'is_dummy',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'planned_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'full' => 'boolean',
        'actually_taken' => 'boolean',
        'is_dummy' => 'boolean',
    ];

    /**
     * Get the employee record associated with the employee event.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
