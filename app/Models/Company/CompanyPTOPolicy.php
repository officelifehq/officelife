<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Define the Paid Time Off (the holidays an employee can take during a
 * year) policy for the company.
 */
class CompanyPTOPolicy extends Model
{
    use HasFactory;

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
        'created_at',
    ];

    /**
     * Get the company record associated with the company news.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the company PTO policy records associated with the company.
     *
     * @return HasMany
     */
    public function calendars()
    {
        return $this->hasMany(CompanyCalendar::class, 'company_pto_policy_id', 'id');
    }

    /**
     * Transform the object to an array representing this object.
     *
     * @return array
     */
    public function toObject(): array
    {
        return [
            'id' => $this->id,
            'company' => [
                'id' => $this->company_id,
            ],
            'year' => $this->year,
            'total_worked_days' => $this->total_worked_days,
            'default_amount_of_allowed_holidays' => $this->default_amount_of_allowed_holidays,
            'default_amount_of_sick_days' => $this->default_amount_of_sick_days,
            'default_amount_of_pto_days' => $this->default_amount_of_pto_days,
            'created_at' => $this->created_at,
        ];
    }
}
