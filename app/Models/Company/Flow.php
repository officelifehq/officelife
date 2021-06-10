<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Flow extends Model
{
    use HasFactory;

    /**
     * Possible flow type.
     */
    const DATE_BASED = 'date';
    const EVENT_BASED = 'event';

    /**
     * Possible triggers.
     */
    const TRIGGER_HIRING_DATE = 'hiring_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'type',
        'trigger',
        'anniversary',
        'paused',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'anniversary' => 'boolean',
        'paused' => 'boolean',
    ];

    /**
     * Get the company record associated with the flow.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the step records associated with the flow.
     *
     * @return HasMany
     */
    public function steps()
    {
        return $this->hasMany(Step::class)->orderBy('real_number_of_days', 'asc');
    }
}
