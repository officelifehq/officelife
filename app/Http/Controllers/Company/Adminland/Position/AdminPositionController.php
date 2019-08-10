<?php

namespace App\Http\Controllers\Company\Adminland\Position;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
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
        $company = Cache::get('currentCompany');
        $positions = PositionResource::collection(
            $company->positions()->orderBy('title', 'asc')->get()
        );

        return View::component('ShowAccountPositions', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'notifications' => auth()->user()->getLatestNotifications($company),
            'positions' => $positions,
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
        $request = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
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
        $request = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
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
        $request = [
            'company_id' => $companyId,
            'position_id' => $positionId,
            'author_id' => auth()->user()->id,
        ];

        (new DestroyPosition)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
