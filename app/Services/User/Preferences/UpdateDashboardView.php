<?php

namespace App\Services\User\Preferences;

use App\Models\User\User;
use App\Services\BaseService;
use App\Models\Company\Company;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
            'user_id' => 'required|integer|exists:users,id',
            'company_id' => 'nullable|integer|exists:companies,id',
            'view' => [
                'required',
                Rule::in([
                    'me',
                    'company',
                    'hr',
                    'team',
                ]),
            ],
        ];
    }

    /**
     * Saves the tab the user was in when viewing the dashboard.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data): bool
    {
        $this->validate($data);

        $user = User::findOrFail($data['user_id']);

        if (! empty($data['company_id'])) {
            $company = Company::findOrFail($data['company_id']);

            if (is_null($user->getEmployeeObjectForCompany($company))) {
                throw new ModelNotFoundException();
            }
        }

        $user->default_dashboard_view = $data['view'];
        $user->save();

        return true;
    }
}
