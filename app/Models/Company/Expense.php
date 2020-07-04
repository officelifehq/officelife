<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'expense_category_id',
        'status',
        'title',
        'amount',
        'currency',
        'description',
        'expensed_at',
        'manager_approver_id',
        'manager_approver_name',
        'manager_approver_approved_at',
        'accounting_approver_id',
        'accounting_approver_name',
        'accounting_approver_approved_at',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'status',
        'title',
        'amount',
        'currency',
        'description',
        'expense_date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expensed_at',
    ];

    /**
     * Get the employee record associated with the expense.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the expense category record associated with the expense.
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    /**
     * Get the manager record associated with the expense.
     *
     * @return BelongsTo
     */
    public function managerApprover()
    {
        return $this->belongsTo(Employee::class, 'manager_approver_id');
    }

    /**
     * Get the person in accounting who has approved the expense record
     * associated with the expense.
     *
     * @return BelongsTo
     */
    public function accountingApprover()
    {
        return $this->belongsTo(Employee::class, 'accounting_approver_id');
    }
}
