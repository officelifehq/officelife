<?php

namespace App\Services\Company\Employee\ECoffee;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\ECoffee;
use App\Models\Company\Employee;
use App\Models\Company\ECoffeeMatch;
use App\Exceptions\NotEnoughPermissionException;

class MarkECoffeeSessionAsHappened extends BaseService
{
    private Employee $employee;

    private array $data;

    private ECoffeeMatch $eCoffeeMatch;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'e_coffee_id' => 'required|integer|exists:e_coffees,id',
            'e_coffee_match_id' => 'required|integer|exists:e_coffee_matches,id',
        ];
    }

    /**
     * Mark an eCoffee session between two employees as happened.
     */
    public function execute(array $data): void
    {
        $this->data = $data;

        $this->validate();
        $this->markAsHappened();
        $this->log();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        ECoffee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['e_coffee_id']);

        $this->eCoffeeMatch = ECoffeeMatch::where('e_coffee_id', $this->data['e_coffee_id'])
            ->findOrFail($this->data['e_coffee_match_id']);

        if ($this->eCoffeeMatch->employee_id != $this->data['author_id'] && $this->eCoffeeMatch->with_employee_id != $this->data['author_id']) {
            throw new NotEnoughPermissionException();
        }
    }

    private function markAsHappened(): void
    {
        $this->eCoffeeMatch->happened = true;
        $this->eCoffeeMatch->save();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'e_coffee_match_session_as_happened',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $this->eCoffeeMatch->employee->id,
                'employee_name' => $this->eCoffeeMatch->employee->name,
                'other_employee_id' => $this->eCoffeeMatch->employeeMatchedWith->id,
                'other_employee_name' => $this->eCoffeeMatch->employeeMatchedWith->name,
            ]),
        ])->onQueue('low');
    }
}
