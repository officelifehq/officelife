<?php

namespace App\Services\Company\Team\Ship;

use Carbon\Carbon;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttachEmployeeToShip extends BaseService
{
    private array $data;

    private Employee $employee;

    private Team $team;

    private Ship $ship;

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
            'ship_id' => 'required|integer|exists:ships,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Associate an employee with a recent ship entry.
     *
     * @param array $data
     *
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->data = $data;

        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($data);
        $this->author = Employee::find($data['author_id']);

        $this->ship = Ship::find($data['ship_id']);
        $this->team = $this->ship->team;

        if ($this->team->company_id != $data['company_id']) {
            throw new ModelNotFoundException(trans('app.error_wrong_team_id'));
        }

        $this->ship->employees()->syncWithoutDetaching([
            $data['employee_id'],
        ]);

        $this->addNotification();

        $this->log();

        $this->employee->refresh();

        return $this->employee;
    }

    /**
     * Add a notification in the UI for the employee.
     */
    private function addNotification(): void
    {
        NotifyEmployee::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_attached_to_recent_ship',
            'objects' => json_encode([
                'ship_id' => $this->ship->id,
                'ship_title' => $this->ship->title,
            ]),
        ])->onQueue('low');
    }

    /**
     * Add the logs in the different audit logs.
     */
    private function log(): void
    {
        $dataToLog = [
            'employee_id' => $this->employee->id,
            'employee_name' => $this->employee->name,
            'team_id' => $this->team->id,
            'team_name' => $this->team->name,
            'ship_id' => $this->ship->id,
            'ship_title' => $this->ship->title,
        ];

        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'employee_attached_to_recent_ship',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($this->data, 'is_dummy'),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_attached_to_recent_ship',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode($dataToLog),
            'is_dummy' => $this->valueOrFalse($this->data, 'is_dummy'),
        ])->onQueue('low');
    }
}
