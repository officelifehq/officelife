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
                ]),
                'max:255',
            ],
            'objects' => 'required|json',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create a notification for the employee.
     * A notification is a small warning in the UI that the user will see when
     * he logs in.
     *
     * @param array $data
     * @return Notification
     */
    public function execute(array $data): Notification
    {
        $this->validateRules($data);

        $notification = Notification::create([
            'employee_id' => $data['employee_id'],
            'action' => $data['action'],
            'objects' => $data['objects'],
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $notification;
    }
}
