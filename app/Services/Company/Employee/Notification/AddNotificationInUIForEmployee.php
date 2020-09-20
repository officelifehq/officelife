<?php

namespace App\Services\Company\Employee\Notification;

use App\Services\BaseService;
use Illuminate\Validation\Rule;
use App\Models\Company\Notification;

class AddNotificationInUIForEmployee extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required|integer|exists:employees,id',
            'action' => [
                'required',
                Rule::in([
                    'task_assigned',
                    'employee_status_assigned',
                    'dummy_data_generated',
                    'employee_added_to_company',
                    'employee_added_to_team',
                    'employee_removed_from_team',
                    'team_lead_set',
                    'team_lead_removed',
                    'employee_attached_to_recent_ship',
                    'expense_assigned_for_validation',
                    'expense_accepted_by_accounting',
                    'expense_accepted_by_manager',
                    'expense_rejected_by_manager',
                    'expense_rejected_by_accounting',
                    'employee_allowed_to_manage_expenses',
                ]),
                'max:255',
            ],
            'objects' => 'required|json',
        ];
    }

    /**
     * Create a notification for the employee.
     * A notification is a small warning in the UI that the user will see when
     * he logs in.
     *
     * @param array $data
     *
     * @return Notification
     */
    public function execute(array $data): Notification
    {
        $this->validateRules($data);

        $notification = Notification::create([
            'employee_id' => $data['employee_id'],
            'action' => $data['action'],
            'objects' => $data['objects'],
        ]);

        return $notification;
    }
}
