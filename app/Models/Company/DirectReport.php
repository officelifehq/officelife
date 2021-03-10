<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DirectReport extends Model
{
    use HasFactory;

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
     * The manager that belong to the direct report.
     *
     * @return HasOne
     */
    public function manager()
    {
        return $this->hasOne(Employee::class, 'id', 'manager_id');
    }

    /**
     * The direct reports that belong to the direct report.
     *
     * @return HasOne
     */
    public function directReport()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }
}
