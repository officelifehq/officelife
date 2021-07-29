<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecruitingStage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recruiting_stages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'recruiting_stage_template_id',
        'name',
        'position',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'position' => 'integer',
    ];

    /**
     * Get the recruiting stage template record associated with the recruiting stage.
     *
     * @return BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(RecruitingStageTemplate::class, 'recruiting_stage_template_id');
    }
}
