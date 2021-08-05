<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobOpeningStage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_opening_stages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_opening_id',
        'recruiting_stage_id',
    ];

    /**
     * Get the company record associated with the job opening.
     *
     * @return BelongsTo
     */
    public function jobOpening()
    {
        return $this->belongsTo(JobOpening::class);
    }
}
