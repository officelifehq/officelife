<?php

namespace App\Services\Company\Employee\Notification;

use App\Services\BaseService;
use App\Models\Company\Notification;
use App\Exceptions\NotEnoughPermissionException;

class MarkNotificationsAsRead extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'employee_id' => 'required|integer|exists:employees,id',
        ];
    }

    /**
     * Mark all notifications as read for the given employee.
     * Only the employee can mark the notifications as read.
     *
     * @param array $data
     *
     * @return bool
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        if ($data['author_id'] != $data['employee_id']) {
            throw new NotEnoughPermissionException();
        }

        $this->validateEmployeeBelongsToCompany($data);

        Notification::where('employee_id', $data['employee_id'])
            ->update(['read' => true]);

        return true;
    }
}
