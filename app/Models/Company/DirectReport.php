<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class DirectReport extends Model
{
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
     */
    public function manager()
    {
        return $this->hasOne(Employee::class, 'id', 'manager_id');
    }

    /**
     * The direct reports that belong to the direct report.
     */
    public function directReport()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }
}
