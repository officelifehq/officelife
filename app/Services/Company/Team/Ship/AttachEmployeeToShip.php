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
use App\Services\QueuableService;
use App\Services\DispatchableService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttachEmployeeToShip extends BaseService implements QueuableService
{
    use DispatchableService;

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
        $this->handle();

        $this->employee->refresh();

        return $this->employee;
    }

    /**
     * Associate an employee with a recent ship entry.
     */
    public function handle(): void
    {
        $this->validate();

        $this->ship->employees()->syncWithoutDetaching([
            $this->data['employee_id'],
        ]);

        $this->addNotification();

        $this->log();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);
        $this->author = Employee::find($this->data['author_id']);

        $this->ship = Ship::find($this->data['ship_id']);
        $this->team = $this->ship->team;

        if ($this->team->company_id != $this->data['company_id']) {
            throw new ModelNotFoundException(trans('app.error_wrong_team_id'));
        }
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
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_attached_to_recent_ship',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode($dataToLog),
        ])->onQueue('low');
    }
}
