<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Candidate extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'candidates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'job_opening_id',
        'employee_id',
        'name',
        'email',
        'uuid',
        'url',
        'desired_salary',
        'highest_reached_stage_id',
        'application_completed',
        'rejected',
        'notes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'application_completed' => 'boolean',
        'rejected' => 'boolean',
    ];

    /**
     * Get the company record associated with the candidate.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
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

    /**
     * Get the job opening associated with the candidate.
     *
     * @return BelongsTo
     */
    public function jobOpening()
    {
        return $this->belongsTo(JobOpening::class);
    }

    /**
     * Get the employee associated with the candidate.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the job opening associated with the candidate.
     *
     * @return HasMany
     */
    public function stages()
    {
        return $this->hasMany(CandidateStage::class);
    }
}
