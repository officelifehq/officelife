<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Company\Adminland\Position\CreatePosition;
use App\Services\Company\Adminland\Position\UpdatePosition;
use App\Services\Company\Adminland\Position\DestroyPosition;
use App\Http\Resources\Company\Position\Position as PositionResource;

class AdminPositionController extends Controller
{
    /**
     * Show the list of positions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $positions = $company->positions()->orderBy('title', 'asc')->get();

        $positionsCollection = collect([]);
        foreach ($positions as $position) {
            $positionsCollection->push([
                'id' => $position->id,
                'title' => $position->title,
            ]);
        }

        return Inertia::render('Adminland/Position/Index', [
            'notifications' => Auth::user()->getLatestNotifications($company),
            'positions' => $positionsCollection,
        ]);
    }

    /**
     * Create the position.
     *
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'title' => $request->get('title'),
        ];

        $position = (new CreatePosition)->execute($request);

        return new PositionResource($position);
    }

    /**
     * Update the position.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $positionId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $companyId, $positionId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'position_id' => $positionId,
            'title' => $request->get('title'),
        ];

        $position = (new UpdatePosition)->execute($request);

        return new PositionResource($position);
    }

    /**
     * Delete the position.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $positionId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $companyId, $positionId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'position_id' => $positionId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyPosition)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
