<?php

namespace App\Http\Controllers\Company\Adminland;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Adminland\AdminPositionViewHelper;
use App\Services\Company\Adminland\Position\CreatePosition;
use App\Services\Company\Adminland\Position\DestroyPosition;
use App\Services\Company\Adminland\Position\UpdatePosition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminPositionController extends Controller
{
    /**
     * Show the list of positions.
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $positions = $company->positions()->orderBy('title', 'asc')->get();
        $positionCollection = AdminPositionViewHelper::list($company);

        return Inertia::render('Adminland/Position/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'positions' => $positionCollection,
        ]);
    }

    /**
     * Create the position.
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'title' => $request->input('title'),
        ];

        $position = (new CreatePosition)->execute($data);

        return response()->json([
            'data' => [
                'id' => $position->id,
                'title' => $position->title,
            ],
        ], 201);
    }

    /**
     * Update the position.
     */
    public function update(Request $request, int $companyId, int $positionId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'position_id' => $positionId,
            'title' => $request->input('title'),
        ];

        $position = (new UpdatePosition)->execute($data);

        return response()->json([
            'data' => [
                'id' => $position->id,
                'title' => $position->title,
            ],
        ], 200);
    }

    /**
     * Delete the position.
     */
    public function destroy(Request $request, int $companyId, int $positionId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'position_id' => $positionId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyPosition)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
