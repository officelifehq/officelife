<?php

namespace App\Http\Controllers\Company\Adminland;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DateHelper;
use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Models\Company\Software;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\File\UploadFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Dashboard\DashboardMeViewHelper;
use App\Http\ViewHelpers\Adminland\AdminHardwareViewHelper;
use App\Http\ViewHelpers\Adminland\AdminSoftwareViewHelper;
use App\Services\Company\Adminland\Software\CreateSoftware;
use App\Services\Company\Adminland\Software\UpdateSoftware;
use App\Services\Company\Adminland\Software\DestroySoftware;
use App\Services\Company\Adminland\Software\AddFileToSoftware;
use App\Services\Company\Adminland\Software\GiveSeatToEmployee;
use App\Services\Company\Adminland\Software\DestroySoftwareFile;
use App\Services\Company\Adminland\Software\TakeSeatFromEmployee;
use App\Services\Company\Adminland\Software\GiveSeatToEveryEmployee;

class AdminSoftwareController extends Controller
{
    /**
     * Show the list of softwares.
     *
     * @return Response
     */
    public function index(): Response
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $softwares = $loggedCompany->softwares()->with('employees')->orderBy('id', 'desc')->get();
        $softwareInformation = AdminSoftwareViewHelper::index($softwares, $loggedCompany);

        return Inertia::render('Adminland/Software/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'softwares' => $softwareInformation,
        ]);
    }

    /**
     * Show the Create software view.
     *
     * @return Response
     */
    public function create(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employees = AdminHardwareViewHelper::employeesList($company);

        return Inertia::render('Adminland/Software/Create', [
            'employees' => $employees,
            'currencies' => DashboardMeViewHelper::currencies(),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Create the software.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $purchasedAt = null;
        if ($request->input('purchased_date_year')) {
            $purchasedAt = Carbon::create(
                intval($request->input('purchased_date_year')),
                intval($request->input('purchased_date_month')),
                intval($request->input('purchased_date_day'))
            );
        }

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
            'seats' => $request->input('seats'),
            'product_key' => $request->input('product_key'),
            'website' => $request->input('website'),
            'licensed_to_name' => $request->input('licensed_to_name'),
            'licensed_to_email_address' => $request->input('licensed_to_email_address'),
            'order_number' => $request->input('order_number'),
            'purchase_amount' => $request->input('purchase_amount'),
            'currency' => $request->input('currency'),
            'purchased_at' => $purchasedAt ? $purchasedAt->format('Y-m-d') : null,
        ];

        (new CreateSoftware)->execute($data);

        return response()->json([
            'data' => route('software.index', [
                'company' => $company,
            ]),
        ], 201);
    }

    /**
     * Show the software.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $softwareId
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $softwareId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $software = Software::where('company_id', $company->id)
                ->with('employees')
                ->findOrFail($softwareId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $employees = $software->employees()->get();
        $employeeCollection = AdminSoftwareViewHelper::seats($employees, $company);
        $information = AdminSoftwareViewHelper::show($software);
        $files = AdminSoftwareViewHelper::files($software, $employee);

        return Inertia::render('Adminland/Software/Show', [
            'software' => $information,
            'employees' => $employeeCollection,
            'files' => $files,
            'uploadcarePublicKey' => config('officelife.uploadcare_public_key'),
            'url_edit' => route('software.edit', [
                'company' => $company,
                'software' => $software,
            ]),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Search a potential employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $softwareId
     * @return JsonResponse
     */
    public function potentialEmployees(Request $request, int $companyId, int $softwareId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();

        $software = Software::where('company_id', $company->id)
            ->findOrFail($softwareId);

        $employees = AdminSoftwareViewHelper::getPotentialEmployees(
            $software,
            $company,
            $request->input('searchTerm')
        );

        return response()->json([
            'data' => $employees,
        ]);
    }

    /**
     * Attach an employee to the software.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $softwareId
     * @return JsonResponse
     */
    public function attach(Request $request, int $companyId, int $softwareId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new GiveSeatToEmployee)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'software_id' => $softwareId,
            'employee_id' => $request->input('employeeId'),
            'product_key' => null,
            'notes' => null,
        ]);

        $employee = Employee::findOrFail($request->input('employeeId'));

        return response()->json([
            'data' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 21),
                'url' => route('employees.show', [
                    'company' => $loggedCompany,
                    'employee' => $employee,
                ]),
            ],
        ]);
    }

    /**
     * Attach all the employees to the software.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $softwareId
     * @return JsonResponse
     */
    public function attachAll(Request $request, int $companyId, int $softwareId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $software = (new GiveSeatToEveryEmployee)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'software_id' => $softwareId,
        ]);

        $employees = $software->employees()->get();
        $employeeCollection = AdminSoftwareViewHelper::seats($employees, $loggedCompany);

        return response()->json([
            'data' => $employeeCollection,
        ]);
    }

    /**
     * Detach an employee from the software.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $softwareId
     * @param integer $employeeId
     * @return JsonResponse
     */
    public function detach(Request $request, int $companyId, int $softwareId, int $employeeId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new TakeSeatFromEmployee)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'software_id' => $softwareId,
            'employee_id' => $employeeId,
        ]);

        return response()->json([
            'data' => true,
        ]);
    }

    /**
     * Get the current number of employees who don't have the given software.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $softwareId
     * @return JsonResponse
     */
    public function numberOfEmployeesWhoDontHaveSoftware(Request $request, int $companyId, int $softwareId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $software = Software::where('company_id', $loggedCompany->id)
            ->findOrFail($softwareId);

        $employeesCount = $software->employees()->count();
        $numberOfEmployeesWithoutSoftware = $loggedCompany->employees()->notLocked()->count() - $employeesCount;

        return response()->json([
            'data' => $numberOfEmployeesWithoutSoftware,
        ]);
    }

    /**
     * Edit the software.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $softwareId
     * @return mixed
     */
    public function edit(Request $request, int $companyId, int $softwareId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $software = Software::where('company_id', $company->id)
                ->with('employees')
                ->findOrFail($softwareId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $software = AdminSoftwareViewHelper::edit($software);

        return Inertia::render('Adminland/Software/Edit', [
            'software' => $software,
            'currencies' => DashboardMeViewHelper::currencies(),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Update the software.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $softwareId
     * @return mixed
     */
    public function update(Request $request, int $companyId, int $softwareId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $purchasedAt = null;
        if ($request->input('purchased_date_year')) {
            $purchasedAt = Carbon::create(
                intval($request->input('purchased_date_year')),
                intval($request->input('purchased_date_month')),
                intval($request->input('purchased_date_day'))
            );
        }

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'software_id' => $softwareId,
            'name' => $request->input('name'),
            'seats' => $request->input('seats'),
            'product_key' => $request->input('product_key'),
            'website' => $request->input('website'),
            'licensed_to_name' => $request->input('licensed_to_name'),
            'licensed_to_email_address' => $request->input('licensed_to_email_address'),
            'order_number' => $request->input('order_number'),
            'purchase_amount' => $request->input('purchase_amount'),
            'currency' => $request->input('currency'),
            'purchased_at' => $purchasedAt ? $purchasedAt->format('Y-m-d') : null,
        ];

        $software = (new UpdateSoftware)->execute($data);

        return response()->json([
            'data' => route('software.show', [
                'company' => $company,
                'software' => $software,
            ]),
        ], 200);
    }

    /**
     * Destroy the software.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $softwareId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $softwareId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new DestroySoftware)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'software_id' => $softwareId,
        ]);

        return response()->json([
            'data' => true,
        ]);
    }

    /**
     * Store a file.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $softwareId
     * @return JsonResponse|null
     */
    public function storeFile(Request $request, int $companyId, int $softwareId): ?JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $software = Software::where('company_id', $loggedCompany->id)
                ->findOrFail($softwareId);
        } catch (ModelNotFoundException $e) {
            return null;
        }

        $file = (new UploadFile)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'uuid' => $request->input('uuid'),
            'name' => $request->input('name'),
            'original_url' => $request->input('original_url'),
            'cdn_url' => $request->input('cdn_url'),
            'mime_type' => $request->input('mime_type'),
            'size' => $request->input('size'),
            'type' => 'project_file',
        ]);

        (new AddFileToSoftware)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'software_id' => $software->id,
            'file_id' => $file->id,
        ]);

        return response()->json([
            'data' => [
                'id' => $file->id,
                'size' => FileHelper::getSize($file->size),
                'filename' => $file->name,
                'download_url' => $file->cdn_url,
                'uploader' => [
                    'id' => $loggedEmployee->id,
                    'name' => $loggedEmployee->name,
                    'avatar' => ImageHelper::getAvatar($loggedEmployee, 24),
                    'url' => route('employees.show', [
                        'company' => $loggedCompany,
                        'employee' => $loggedEmployee,
                    ]),
                ],
                'created_at' => DateHelper::formatDate($file->created_at, $loggedEmployee->timezone),
            ],
        ], 200);
    }

    /**
     * Destroy a file.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $softwareId
     * @param int $fileId
     * @return JsonResponse|null
     */
    public function destroyFile(Request $request, int $companyId, int $softwareId, int $fileId): ?JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $software = Software::where('company_id', $loggedCompany->id)
                ->findOrFail($softwareId);
        } catch (ModelNotFoundException $e) {
            return null;
        }

        (new DestroySoftwareFile)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'software_id' => $software->id,
            'file_id' => $fileId,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
