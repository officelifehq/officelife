<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    use HasFactory;

    protected $table = 'employee_daily_calendar_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'log_date',
        'new_balance',
        'daily_accrued_amount',
        'current_holidays_per_year',
        'default_amount_of_allowed_holidays_in_company',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'log_date',
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
