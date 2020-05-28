<?php

namespace App\Http\Controllers\API\Company\Team;

use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Adminland\Team\CreateTeam;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Company\Team\Team as TeamResource;

class ApiTeamController extends ApiController
{
    /**
     * Get the list of teams.
     *
     * @param int $companyId
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(Request $request, int $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $teams = $company->teams()->get();

        return TeamResource::collection($teams);
    }

    /**
     * Get the detail of a given team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $companyId, $teamId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $team = Team::where('company_id', $company->id)
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
     * @param Request $request
     * @param int $companyId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $companyId)
    {
        try {
            $team = app(CreateTeam::class)->execute(
                $request->all()
                    +
                    [
                    'company_id' => $companyId,
                    'author_id' => Auth::user()->id,
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
