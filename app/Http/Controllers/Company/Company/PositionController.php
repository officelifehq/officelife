<?php

namespace App\Http\Controllers\Company\Company;

use Inertia\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;

class PositionController extends Controller
{
    /**
     * Get the list of positions in the company.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        return response()->json([
            'data' => EmployeeShowViewHelper::positions($loggedCompany),
        ], 200);
    }
}
