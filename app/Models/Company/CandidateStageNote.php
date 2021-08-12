<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandidateStageNote extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'candidate_stage_notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'note',
        'author_name',
    ];

    /**
     * Get the candidate stage associated with the candidate stage note.
     *
     * @return BelongsTo
     */
    public function candidateStage()
    {
        return $this->belongsTo(candidateStage::class);
    }

    /**
     * Get the employee associated with the candidate stage note.
     *
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }
}
