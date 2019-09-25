<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Defines what the Paid Time Off (the holidays an employee can take during a
 * year).
 */
class CompanyPTOPolicy extends Model
{
    protected $table = 'company_pto_policies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'year',
        'total_worked_days',
        'default_amount_of_allowed_holidays',
        'default_amount_of_sick_days',
        'default_amount_of_pto_days',
        'is_dummy',
        'created_at',
    ];

    /**
     * Get the company record associated with the company news.
     *
     * @return belongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
