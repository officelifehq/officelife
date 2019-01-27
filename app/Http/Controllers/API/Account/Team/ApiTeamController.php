<?php

namespace App\Http\Controllers\API\Account\Team;

use App\Models\Account\Team;
use Illuminate\Http\Request;
use App\Services\Account\Team\CreateTeam;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Account\Team\Team as TeamResource;

class ApiTeamController extends ApiController
{
    /**
     * Get the list of teams.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $team = auth()->user()->account->teams()->get();

        return TeamResource::collection($team);
    }

    /**
     * Get the detail of a given team.
     *
     * @param  Request $request
     * @param int $teamId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $teamId)
    {
        try {
            $team = Team::where('account_id', auth()->user()->account_id)
                ->where('id', $teamId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound();
        }

        return new TeamResource($team);
    }

    /**
     * Create a team.
     *
     * @param  Request $request
     * @param int $teamId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $team = app(CreateTeam::class)->execute(
                $request->all()
                    +
                    [
                    'account_id' => auth()->user()->account_id,
                    'author_id' => auth()->user()->id,
                ]
            );
        } catch (ValidationException $e) {
            return $this->respondValidatorFailed($e->validator);
        } catch (NotEnoughPermissionException $e) {
            return $this->respondUnauthorized();
        }

        return new TeamResource($team);
    }
}
