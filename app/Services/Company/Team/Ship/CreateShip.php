<?php

namespace App\Services\Company\Team\Ship;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateShip extends BaseService
{
    private array $data;

    private Team $team;

    private Ship $ship;

    private Collection $employees;

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
            'team_id' => 'required|integer|exists:teams,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
            'employees' => 'nullable|array',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create a ship - something that the team has recently shipped.
     *
     * @param array $data
     *
     * @return Ship
     */
    public function execute(array $data): Ship
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->team = $this->validateTeamBelongsToCompany($data);

        $this->validateEmployees();

        $this->data = $data;

        $this->createShip();

        $this->attachEmployees();

        $this->log();

        return $this->ship;
    }

    /**
     * Make sure employees that are given in parameter of the service exist
     * in the company.
     */
    private function validateEmployees(): void
    {
        if (! $this->data['employees']) {
            return;
        }

        foreach ($this->data['employees'] as $employeeId) {
            try {
                $employee = Employee::where('company_id', $this->data['company_id'])
                    ->find($employeeId);
            } catch (ModelNotFoundException $e) {
                continue;
            }

            $this->employees->push($employee);
        }
    }

    /**
     * Actually create the ship.
     */
    private function createShip(): void
    {
        $this->ship = Ship::create([
            'team_id' => $this->data['team_id'],
            'title' => $this->data['title'],
            'description' => $this->valueOrNull($this->data, 'description'),
            'is_dummy' => $this->valueOrFalse($this->data, 'is_dummy'),
        ]);
    }

    /**
     * Attach the ship to the employees who did it, if provided in the call of
     * the service.
     */
    private function attachEmployees(): void
    {
        if ($this->employees->count() == 0) {
            return;
        }

        foreach ($this->employees as $employee) {
            $this->ship->employees()->syncWithoutDetaching([$employee->id]);
        }
    }

    /**
     * Add logs.
     */
    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_useful_link_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'link_id' => $link->id,
                'link_name' => $link->label,
                'team_id' => $team->id,
                'team_name' => $team->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $data['team_id'],
            'action' => 'useful_link_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'link_id' => $link->id,
                'link_name' => $link->label,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');
    }
}
