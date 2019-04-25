<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Action extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'step_id',
        'nature',
        'recipient',
        'specific_recipient_information',
    ];

    /**
     * Get the step record associated with the action.
     *
     * @return BelongsTo
     */
    public function step()
    {
        return $this->belongsTo(Step::class);
    }
}
