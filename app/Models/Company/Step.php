<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * A step has three components, like "3 days before".
 * There are 3 possible cases:
 *  - "7 days before"
 *  - "The same day"
 *  - "7 days after".
 *
 *  There are 3 components for this object:
 *   - the number: 7
 *   - the unit of time: day|week|month
 *   - the modifier: before|after|same_day
 */
class Step extends Model
{
    use HasFactory;

    /**
     * Possible modifiers.
     */
    const MODIFIER_BEFORE = 'before';
    const MODIFIER_AFTER = 'after';
    const MODIFIER_SAME_DAY = 'same_day';

    /**
     * Possible modifiers.
     */
    const UNIT_DAY = 'day';
    const UNIT_WEEK = 'week';
    const UNIT_MONTH = 'month';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'flow_id',
        'number',
        'unit_of_time',
        'modifier',
        'real_number_of_days',
    ];

    /**
     * Get the flow record associated with the step.
     *
     * @return BelongsTo
     */
    public function flow()
    {
        return $this->belongsTo(Flow::class);
    }

    /**
     * Get the action records associated with the step.
     *
     * @return HasMany
     */
    public function actions()
    {
        return $this->hasMany(Action::class);
    }
}
