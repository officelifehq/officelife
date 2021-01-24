<?php

namespace App\Http\Controllers\Company\Company;

use Inertia\Response;
use App\Models\Company\Company;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;

class PronounController extends Controller
{
    /**
     * Get the list of pronouns in the company.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => EmployeeShowViewHelper::pronouns(),
        ], 200);
    }
}
