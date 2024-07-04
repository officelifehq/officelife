<?php

namespace App\Http\Controllers;

use App\Helpers\InstanceHelper;
use App\Http\ViewHelpers\Company\HeaderSearchViewHelper;
use App\Models\Company\Employee;
use App\Models\Company\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HeaderSearchController extends Controller
{
    /**
     * Perform search of an employee from the header.
     */
    public function employees(Request $request): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $search = $request->input('searchTerm');

        return response()->json([
            'data' => HeaderSearchViewHelper::employees($loggedCompany, $search),
        ], 200);
    }

    /**
     * Perform search of an team from the header.
     */
    public function teams(Request $request): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $search = $request->input('searchTerm');

        return response()->json([
            'data' => HeaderSearchViewHelper::teams($loggedCompany, $search),
        ], 200);
    }
}
