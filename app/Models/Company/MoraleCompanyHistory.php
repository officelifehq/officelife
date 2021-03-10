<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoraleCompanyHistory extends Model
{
    use HasFactory;

    protected $table = 'morale_company_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'average',
        'number_of_employees',
    ];

    /**
     * Get the company record associated with the morale company history object.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
