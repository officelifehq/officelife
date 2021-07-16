<?php

namespace App\Http\Controllers\Company\Company\HR;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Models\Company\Position;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\HR\CompanyHRPositionShowViewHelper;

class CompanyHRPositionController extends Controller
{
    /**
     * Show the HR main page on the Company tab.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $positionId
     * @return Response
     */
    public function show(Request $request, int $companyId, int $positionId): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $position = Position::where('company_id', $company->id)
                ->findOrFail($positionId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $data = CompanyHRPositionShowViewHelper::show($company, $position);

        return Inertia::render('Company/HR/Positions/Show', [
            'data' => $data,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }
}
