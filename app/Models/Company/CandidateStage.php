<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CandidateStage extends Model
{
    use HasFactory;

    /**
     * Possible status of a candidate stage.
     */
    const STATUS_TO_SORT = 'to_sort';
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
        'job_opening_recruiting_stage_id',
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
     * Get the file entries associated with the candidate.
     *
     * @return BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class);
    }
}
