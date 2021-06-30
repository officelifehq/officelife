<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyDailyUsageHistory extends Model
{
    use HasFactory;

    protected $table = 'company_daily_usage_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'number_of_active_employees',
        'created_at',
    ];

    /**
     * Get the Company record associated with the company usage history object.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the company usage history details records associated with the company
     * usage history.
     *
     * @return HasMany
     */
    public function details()
    {
        return $this->hasMany(CompanyUsageHistoryDetails::class, 'usage_history_id', 'id');
    }
}
