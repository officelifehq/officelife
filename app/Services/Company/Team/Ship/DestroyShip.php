<?php

namespace App\Services\Company\Team\Ship;

use App\Jobs\LogAccountAudit;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Services\BaseService;
use Carbon\Carbon;

class DestroyShip extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:employees,id',
            'company_id' => 'required|integer|exists:companies,id',
            'ship_id' => 'required|integer|exists:ships,id',
        ];
    }

    /**
     * Destroy a ship.
     */
    public function execute(array $data): bool
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $ship = Ship::findOrFail($data['ship_id']);

        Team::where('company_id', $data['company_id'])
            ->findOrFail($ship->team->id);

        $ship->delete();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'ship_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'ship_title' => $ship->title,
                'team_id' => $ship->team->id,
                'team_name' => $ship->team->name,
            ]),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $ship->team->id,
            'action' => 'ship_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'ship_title' => $ship->title,
            ]),
        ])->onQueue('low');

        return true;
    }
}
