<?php

namespace App\Services\Company\Adminland\Hardware;

use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Hardware;
use App\Services\BaseService;
use Carbon\Carbon;

class RegainHardware extends BaseService
{
    private Employee $employee;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'hardware_id' => 'required|integer|exists:hardware,id',
        ];
    }

    /**
     * Lend a piece of hardware to an employee.
     */
    public function execute(array $data): Hardware
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $hardware = Hardware::where('company_id', $data['company_id'])
            ->findOrFail($data['hardware_id']);

        $this->employee = $hardware->employee;

        Hardware::where('id', $hardware->id)->update([
            'employee_id' => null,
        ]);

        $this->log($data, $hardware);

        return $hardware->refresh();
    }

    /**
     * Create an audit log.
     */
    private function log(array $data, Hardware $hardware): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'hardware_regained',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'hardware_id' => $hardware->id,
                'hardware_name' => $hardware->name,
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');
    }
}
