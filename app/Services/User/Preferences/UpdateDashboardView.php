<?php

namespace App\Services\User\Preferences;

use App\Models\User\User;
use App\Services\BaseService;
use Illuminate\Validation\Rule;
use App\Models\Company\Employee;

class UpdateDashboardView extends BaseService
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
            'company_id' => 'nullable|integer|exists:companies,id',
            'view' => [
                'required',
                Rule::in([
                    'me',
                    'company',
                    'hr',
                    'timesheet',
                    'team',
                    'manager',
                    'expenses',
                ]),
            ],
        ];
    }

    /**
     * Saves the tab the user was in when viewing the dashboard.
     *
     * @param array $data
     *
     * @return bool
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $employee = Employee::where('id', $data['employee_id'])
            ->where('company_id', $data['company_id'])
            ->firstOrFail();

        $employee->default_dashboard_view = $data['view'];
        $employee->save();

        return true;
    }
}
