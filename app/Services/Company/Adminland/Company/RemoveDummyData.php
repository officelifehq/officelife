<?php

namespace App\Services\Company\Adminland\Company;

use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RemoveDummyData extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
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
     * @return void
     */
    public function execute(array $data) : void
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('villagers.authorizations.administrator')
        );

        $company = Company::find($data['company_id']);

        $this->removeTeams($data);

        $this->removeEmployees($data);

        $this->removeAuditLogs($data);

        $company->has_dummy_data = false;
        $company->save();

        $cachedCompanyObject = 'cachedCompanyObject_'.$data['author_id'];
        Cache::put($cachedCompanyObject, $company, now()->addMinutes(60));
    }

    /**
     * Remove dummy team.
     *
     * @param array $data
     * @return void
     */
    private function removeTeams(array $data) : void
    {
        DB::table('teams')
            ->where('company_id', $data['company_id'])
            ->where('is_dummy', true)
            ->delete();
    }

    /**
     * Remove dummy users.
     *
     * @param array $data
     * @return void
     */
    private function removeEmployees(array $data) : void
    {
        $employees = Employee::where('company_id', $data['company_id'])->get();

        foreach ($employees as $employee) {
            if ($employee->is_dummy) {
                $employee->delete();
            }
        }
    }

    /**
     * Remove dummy audit logs.
     *
     * @param array $data
     * @return void
     */
    private function removeAuditLogs(array $data) : void
    {
        DB::table('audit_logs')
            ->where('company_id', $data['company_id'])
            ->where('is_dummy', true)
            ->delete();
    }
}
