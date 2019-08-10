<?php

namespace App\Services\Company\Employee\Notification;

use App\Models\User\User;
use App\Services\BaseService;
use Illuminate\Validation\Rule;
use App\Models\Company\Notification;

class CreateNotificationInUIForEmployee extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'employee_id' => 'required|integer|exists:employees,id',
            'action' => [
                'required',
                Rule::in([
                    'task_assigned',
                    'employee_status_assigned',
                ]),
                'max:255',
            ],
            'content' => 'required|string|max:255',
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
    public function execute(array $data) : Notification
    {
        $this->validate($data);

        $notification = Notification::create([
            'employee_id' => $data['employee_id'],
            'action' => $data['action'],
            'content' => $data['content'],
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $notification;
    }
}
