<?php

namespace App\Services\Company\Adminland\Company;

use App\Models\Company\Team;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\TeamLog;
use App\Models\Company\AuditLog;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\EmployeeLog;

class RemoveDummyData extends BaseService
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
        ];
    }

    /**
     * Generate dummy data for the given account.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastAdministrator()
            ->canExecuteService();

        $company = Company::find($data['company_id']);

        $this->removePositions();
        $this->removeTeams();
        $this->removeEmployees();
        $this->removeAuditLogs();

        $company->has_dummy_data = false;
        $company->save();
    }

    private function removePositions(): void
    {
        Position::where('is_dummy', true)->delete();
    }

    private function removeTeams(): void
    {
        Team::where('is_dummy', true)->delete();
    }

    private function removeEmployees(): void
    {
        Employee::where('is_dummy', true)->delete();
    }

    private function removeAuditLogs(): void
    {
        AuditLog::where('is_dummy', true)->delete();
        EmployeeLog::where('is_dummy', true)->delete();
        TeamLog::where('is_dummy', true)->delete();
    }
}
