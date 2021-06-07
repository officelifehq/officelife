<?php

namespace App\Services\Company\Adminland\Software;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Software;

class GiveSeatToEmployee extends BaseService
{
    protected array $data;
    protected Employee $employee;
    protected Software $software;

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
            'software_id' => 'required|integer|exists:softwares,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'product_key' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:65535',
        ];
    }

    /**
     * Give a copy of a software to an employee.
     *
     * @param array $data
     * @return Software
     */
    public function execute(array $data): Software
    {
        $this->data = $data;
        $this->validate();
        $this->checkOut();
        $this->log();

        return $this->software;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);

        $this->software = Software::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['software_id']);
    }

    private function checkOut(): void
    {
        $this->software->employees()->syncWithoutDetaching([
            $this->data['employee_id'] => [
                'product_key' => $this->valueOrNull($this->data, 'product_key'),
                'notes' => $this->valueOrNull($this->data, 'notes'),
            ],
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'software_seat_given_to_employee',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'software_id' => $this->software->id,
                'software_name' => $this->software->name,
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
            ]),
        ])->onQueue('low');
    }
}
