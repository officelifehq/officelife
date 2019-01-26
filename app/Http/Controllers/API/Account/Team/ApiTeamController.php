<?php

namespace App\Http\Controllers\API\Account\Team;

use App\Models\Account\Team;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Api\ApiController;
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
        try {
            $team = auth()->user()->account->teams()->get();
        } catch (QueryException $e) {
            return $this->respondInvalidQuery();
        }

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
}
