<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Keeps track of what happened during a day.
 * - holiday balance at this date
 * - is this day marked as a sick day
 * - is this day marked as a PTO day
 * - is this day marked as a holiday
 * - is the employee working remote.
 *
 * This data comes from a cron job that is processed after midnight every day.
 */
class EmployeeDailyCalendarEntry extends Model
{
    protected $table = 'employee_daily_calendar_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'new_balance',
        'daily_accrued_amount',
        'current_holidays_per_year',
        'default_amount_of_allowed_holidays_in_company',
        'on_holiday',
        'sick_day',
        'pto_day',
        'remote',
        'is_dummy',
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
