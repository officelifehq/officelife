<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandidateStageParticipant extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'candidate_stage_participants';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'participant_id',
        'candidate_stage_id',
        'participant_name',
        'participated',
        'participated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'participated' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'participated_at',
    ];

    /**
     * Get the candidate stage associated with the candidate stage note.
     *
     * @return BelongsTo
     */
    public function candidateStage()
    {
        return $this->belongsTo(CandidateStage::class);
    }

    /**
     * Get the employee associated with the candidate stage note.
     *
     * @return BelongsTo
     */
    public function participant()
    {
        return $this->belongsTo(Employee::class, 'participant_id');
    }
}
