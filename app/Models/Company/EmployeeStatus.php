<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeStatus extends Model
{
    use LogsActivity;

    protected $table = 'employee_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'is_dummy',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'name',
    ];

    /**
     * Get the company record associated with the employee event.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
