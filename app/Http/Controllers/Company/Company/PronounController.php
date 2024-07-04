<?php

namespace App\Http\Controllers\Company\Company;

use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;
use App\Models\Company\Company;
use Illuminate\Http\JsonResponse;

class PronounController extends Controller
{
    /**
     * Get the list of pronouns in the company.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => EmployeeShowViewHelper::pronouns(),
        ], 200);
    }
}
