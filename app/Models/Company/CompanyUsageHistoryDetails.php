<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyUsageHistoryDetails extends Model
{
    use HasFactory;

    protected $table = 'company_usage_history_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usage_history_id',
        'employee_name',
        'employee_email',
    ];

    /**
     * Get the Company usage history record associated with the company
     * usage history detail.
     *
     * @return BelongsTo
     */
    public function companyUsageHistory()
    {
        return $this->belongsTo(CompanyDailyUsageHistory::class, 'usage_history_id');
    }
}
