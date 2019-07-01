<?php

namespace App\Services\User\Notification;

use App\Models\User\User;
use App\Services\BaseService;
use App\Models\Company\Company;
use Illuminate\Validation\Rule;
use App\Models\User\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateNotificationInUIForEmployee extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'company_id' => 'nullable|integer|exists:companies,id',
            'action' => [
                'required',
                Rule::in([
                    'task_assigned',
                ]),
                'max:255',
            ],
            'content' => 'required|string|max:255',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create a notification for the user.
     * A notification is a small warning in the UI that the user will see when
     * he logs in.
     *
     * @param array $data
     * @return Notification
     */
    public function execute(array $data) : Notification
    {
        $this->validate($data);

        $user = User::findOrFail($data['user_id']);

        if (! empty($data['company_id'])) {
            $company = Company::findOrFail($data['company_id']);

            if (is_null($user->getEmployeeObjectForCompany($company))) {
                throw new ModelNotFoundException();
            }
        }

        $notification = Notification::create([
            'user_id' => $data['user_id'],
            'company_id' => $this->nullOrValue($data, 'company_id'),
            'action' => $data['action'],
            'content' => $data['content'],
        ]);

        return $notification;
    }
}
