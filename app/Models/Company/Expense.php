<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use LogsActivity,
        HasFactory;

    /**
     * Possible status of an expense.
     */
    const CREATED = 'created';
    const AWAITING_MANAGER_APPROVAL = 'manager_approval';
    const AWAITING_ACCOUTING_APPROVAL = 'accounting_approval';
    const REJECTED_BY_MANAGER = 'rejected_by_manager';
    const REJECTED_BY_ACCOUNTING = 'rejected_by_accounting';
    const ACCEPTED = 'accepted';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'employee_id',
        'employee_name',
        'expense_category_id',
        'status',
        'title',
        'amount', // expressed in cents, ie $100,00 = 10000
        'currency',
        'converted_amount',
        'converted_to_currency',
        'converted_at',
        'exchange_rate',
        'description',
        'expensed_at',
        'manager_approver_id',
        'manager_approver_name',
        'manager_approver_approved_at',
        'manager_rejection_explanation',
        'accounting_approver_id',
        'accounting_approver_name',
        'accounting_approver_approved_at',
        'accounting_rejection_explanation',
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
     * The attributes that should be changed.
     *
     * @var array
     */
    protected $casts = [
        'employee_id' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expensed_at',
        'manager_approver_approved_at',
        'accounting_approver_approved_at',
        'converted_at',
    ];

    /**
     * Get the company record associated with the expense.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

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
