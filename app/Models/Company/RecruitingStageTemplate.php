<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecruitingStageTemplate extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recruiting_stage_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
    ];

    /**
     * Get the company record associated with the recruiting stage
     * template.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the recruiting stage records associated with the recruiting stage
     * template.
     *
     * @return HasMany
     */
    public function stages()
    {
        return $this->hasMany(RecruitingStage::class)->orderBy('position', 'asc');
    }

    /**
     * Get the job openings associated with the recruiting stage template.
     *
     * @return HasMany
     */
    public function jobOpenings()
    {
        return $this->hasMany(JobOpening::class);
    }
}
