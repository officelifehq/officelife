<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'sponsored_by_employee_id',
        'active',
        'fulfilled',
        'reference_number',
        'slug',
        'title',
        'description',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'activated_at',
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
     * Get the employee record associated with the job opening.
     *
     * @return BelongsTo
     */
    public function sponsor()
    {
        return $this->belongsTo(Employee::class, 'sponsored_by_employee_id');
    }
}
