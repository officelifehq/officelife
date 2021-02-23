<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Services\Company\Adminland\File\UploadFile;
use App\Http\ViewHelpers\Adminland\AdminUploadEmployeeViewHelper;
use App\Services\Company\Adminland\Employee\StoreEmployeesFromCSVInTemporaryTable;

class AdminUploadEmployeeController extends Controller
{
    /**
     * Show the list of past import employees by csv.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $importJobs = AdminUploadEmployeeViewHelper::index($company);

        return Inertia::render('Adminland/Employee/Archives/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'importJobs' => $importJobs,
        ]);
    }

    /**
     * Show the Upload CSV of employees view.
     *
     * @return Response
     */
    public function upload(): Response
    {
        return Inertia::render('Adminland/Employee/Import', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Upload the CSV.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $file = (new UploadFile)->execute([
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'file' => $request->file('csv'),
        ]);

        (new StoreEmployeesFromCSVInTemporaryTable)->execute([
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'path' => $file->path,
        ]);

        return response()->json([
            'company_id' => $companyId,
        ]);
    }
}
