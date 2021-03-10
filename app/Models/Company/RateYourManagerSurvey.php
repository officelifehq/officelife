<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RateYourManagerSurvey extends Model
{
    use HasFactory;

    protected $table = 'rate_your_manager_surveys';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'manager_id',
        'active',
        'valid_until_at',
        'created_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'valid_until_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the manager record associated with the survey.
     *
     * @return BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Get the answers made by direct reports associated with the survey.
     *
     * @return HasMany
     */
    public function answers()
    {
        return $this->hasMany(RateYourManagerAnswer::class);
    }
}
