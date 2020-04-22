<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Collections\PositionCollection;
use App\Services\Company\Adminland\Position\CreatePosition;
use App\Services\Company\Adminland\Position\UpdatePosition;
use App\Services\Company\Adminland\Position\DestroyPosition;

class AdminPositionController extends Controller
{
    /**
     * Show the list of positions.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $positions = $company->positions()->orderBy('title', 'asc')->get();
        $positionCollection = PositionCollection::prepare($positions);

        return Inertia::render('Adminland/Position/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'positions' => $positionCollection,
        ]);
    }

    /**
     * Create the position.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
     */
    public function store(Request $request, int $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'title' => $request->get('title'),
        ];

        $position = (new CreatePosition)->execute($request);

        return response()->json([
            'data' => $position->toObject(),
        ], 201);
    }

    /**
     * Update the position.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $positionId
     *
     * @return Response
     */
    public function update(Request $request, int $companyId, int $positionId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'position_id' => $positionId,
            'title' => $request->get('title'),
        ];

        $position = (new UpdatePosition)->execute($request);

        return response()->json([
            'data' => $position->toObject(),
        ], 200);
    }

    /**
     * Delete the position.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $positionId
     *
     * @return Response
     */
    public function destroy(Request $request, int $companyId, int $positionId)
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
