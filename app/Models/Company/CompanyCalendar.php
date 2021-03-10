<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represent a calendar year for the company. This is used to know if any given
 * day is off.
 */
class CompanyCalendar extends Model
{
    use HasFactory;

    protected $table = 'company_calendars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_pto_policy_id',
        'day',
        'day_of_week',
        'day_of_year',
        'is_worked',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_worked' => 'boolean',
    ];

    /**
     * Get the company record associated with the company news.
     *
     * @return BelongsTo
     */
    public function policy()
    {
        return $this->belongsTo(CompanyPTOPolicy::class, 'company_pto_policy_id', 'id');
    }
}
