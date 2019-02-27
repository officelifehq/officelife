<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DirectReport extends Model
{
    use LogsActivity;

    protected $table = 'direct_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'manager_id',
        'employee_id',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
    ];
}
