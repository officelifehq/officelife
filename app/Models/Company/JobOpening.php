<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobOpening extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_openings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'position_id',
        'recruiting_stage_template_id',
        'team_id',
        'fulfilled_by_candidate_id',
        'active',
        'fulfilled',
        'reference_number',
        'page_views',
        'slug',
        'title',
        'description',
        'created_at',
        'activated_at',
        'fulfilled_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'activated_at',
        'fulfilled_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'fulfilled' => 'boolean',
    ];

    /**
     * Get the company record associated with the job opening.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the position record associated with the job opening.
     *
     * @return BelongsTo
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * Get the employee records associated with the job opening.
     *
     * @return BelongsToMany
     */
    public function sponsors()
    {
        return $this->belongsToMany(Employee::class, 'job_opening_sponsor')->withTimestamps();
    }

    /**
     * Get the team record associated with the job opening.
     *
     * @return BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Get the recruiting stage template record associated with the job opening.
     *
     * @return BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(RecruitingStageTemplate::class, 'recruiting_stage_template_id');
    }

    /**
     * Get the candidate records associated with the job opening.
     *
     * @return HasMany
     */
    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'job_opening_id');
    }

    /**
     * Get the candidate records associated with the job opening.
     *
     * @return BelongsTo
     */
    public function candidateWhoWonTheJob()
    {
        return $this->belongsTo(Candidate::class, 'fulfilled_by_candidate_id');
    }
}
