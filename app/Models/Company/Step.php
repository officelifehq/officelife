<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Step extends Model
{
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
