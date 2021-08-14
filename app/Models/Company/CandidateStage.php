<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandidateStage extends Model
{
    use HasFactory;

    /**
     * Possible status of a candidate stage.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PASSED = 'passed';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'candidate_stages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'candidate_id',
        'decider_id',
        'stage_name',
        'stage_position',
        'status',
        'name',
        'decided_name',
        'decided_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'decided_at',
    ];

    /**
     * Get the candidate associated with the candidate stage.
     *
     * @return BelongsTo
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Get the employee associated with the candidate stage.
     *
     * @return BelongsTo
     */
    public function decider()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the notes associated with the candidate stage.
     *
     * @return HasMany
     */
    public function notes()
    {
        return $this->hasMany(CandidateStageNote::class);
    }

    /**
     * Get the participants associated with the candidate stage.
     *
     * @return HasMany
     */
    public function participants()
    {
        return $this->hasMany(CandidateStageParticipant::class);
    }
}
