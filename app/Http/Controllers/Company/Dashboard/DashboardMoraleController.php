<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Morale\LogMorale;

class DashboardMoraleController extends Controller
{
    /**
     * Create a morale log.
     *
     * @var Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $employee = InstanceHelper::getLoggedEmployee();

        $request = [
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'emotion' => $request->get('emotion'),
            'comment' => $request->get('comment'),
        ];

        (new LogMorale)->execute($request);

        $employee->refresh();

        return response()->json([
            'data' => true,
        ], 200);
    }
}
