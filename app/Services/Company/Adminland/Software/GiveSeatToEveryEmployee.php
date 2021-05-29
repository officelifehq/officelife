<?php

namespace App\Services\Company\Adminland\Software;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Software;

class GiveSeatToEveryEmployee extends BaseService
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
        ];
    }

    /**
     * Give a copy of a software to every employee in the company who doesn't
     * have it already.
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

        $this->software = Software::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['software_id']);
    }

    private function checkOut(): void
    {
        // get employees in the company who doesn't have the software yet
        $employees = $this->software->employees()
            ->select('id')
            ->pluck('id')
            ->toArray();

        $potentialEmployees = $this->software->company->employees()
            ->notLocked()
            ->whereNotIn('id', $employees)
            ->get();

        foreach ($potentialEmployees as $employee) {
            $this->software->employees()->syncWithoutDetaching([
                $employee->id => [
                    'product_key' => null,
                    'notes' => null,
                ],
            ]);
        }
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'software_seat_given_to_all_remaining_employees',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'software_id' => $this->software->id,
                'software_name' => $this->software->name,
            ]),
        ])->onQueue('low');
    }
}
