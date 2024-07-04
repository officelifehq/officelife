<?php

namespace App\Http\Controllers\Company\Company;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;
use App\Models\Company\Company;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    /**
     * Get the list of positions in the company.
     */
    public function index(): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        return response()->json([
            'data' => EmployeeShowViewHelper::positions($loggedCompany),
        ], 200);
    }
}
