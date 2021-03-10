<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeePlannedHoliday extends Model
{
    use HasFactory;

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
