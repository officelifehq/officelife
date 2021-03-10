<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RateYourManagerAnswer extends Model
{
    use HasFactory;

    protected $table = 'rate_your_manager_answers';

    /**
     * Possible statuses of an answer.
     */
    const BAD = 'bad';
    const AVERAGE = 'average';
    const GOOD = 'good';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rate_your_manager_survey_id',
        'employee_id',
        'active',
        'rating',
        'comment',
        'reveal_identity_to_manager',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'reveal_identity_to_manager' => 'boolean',
    ];

    /**
     * Get the survey record associated with the answer.
     *
     * @return BelongsTo
     */
    public function entry()
    {
        return $this->belongsTo(RateYourManagerSurvey::class, 'rate_your_manager_survey_id');
    }

    /**
     * Get the employee record associated with the answer.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
