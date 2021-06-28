<?php

namespace App\Services\Company\Team\Ship;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;

class CreateShip extends BaseService
{
    private array $data;

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
            'team_id' => 'required|integer|exists:teams,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
            'employees' => 'nullable|array',
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
        $this->data = $data;
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->team = $this->validateTeamBelongsToCompany($data);

        $this->createShip();

        $this->attachEmployees();

        $this->log();

        return $this->ship;
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
        ]);
    }

    /**
     * Attach the ship to the employees who did it, if provided in the call of
     * the service.
     */
    private function attachEmployees(): void
    {
        if (! $this->data['employees']) {
            return;
        }

        foreach ($this->data['employees'] as $key => $employeeId) {
            AttachEmployeeToShip::dispatch([
                'company_id' => $this->data['company_id'],
                'author_id' => $this->data['author_id'],
                'employee_id' => $employeeId,
                'ship_id' => $this->ship->id,
            ])->onQueue('low');
        }
    }

    /**
     * Add logs.
     */
    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'recent_ship_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_id' => $this->team->id,
                'team_name' => $this->team->name,
                'ship_id' => $this->ship->id,
                'ship_title' => $this->ship->title,
            ]),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $this->data['team_id'],
            'action' => 'recent_ship_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'ship_id' => $this->ship->id,
                'ship_title' => $this->ship->title,
            ]),
        ])->onQueue('low');
    }
}
