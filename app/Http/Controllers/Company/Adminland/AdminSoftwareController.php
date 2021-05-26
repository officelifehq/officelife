<?php

namespace App\Http\Controllers\Company\Adminland;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\PaginatorHelper;
use App\Models\Company\Employee;
use App\Models\Company\Software;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Dashboard\DashboardMeViewHelper;
use App\Http\ViewHelpers\Adminland\AdminHardwareViewHelper;
use App\Http\ViewHelpers\Adminland\AdminSoftwareViewHelper;
use App\Services\Company\Adminland\Software\CreateSoftware;
use App\Services\Company\Adminland\Software\GiveSeatToEmployee;

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

        $softwares = $loggedCompany->softwares()->with('employees')->orderBy('id', 'desc')->paginate(10);
        $softwareInformation = AdminSoftwareViewHelper::index($softwares, $loggedCompany);

        return Inertia::render('Adminland/Software/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'softwares' => $softwareInformation,
            'paginator' => PaginatorHelper::getData($softwares),
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

        $expiredAt = null;
        if ($request->input('expiration_date_year')) {
            $expiredAt = Carbon::create(
                intval($request->input('expiration_date_year')),
                intval($request->input('expiration_date_month')),
                intval($request->input('expiration_date_day'))
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
            'expired_at' => $expiredAt ? $expiredAt->format('Y-m-d') : null,
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

        try {
            $software = Software::where('company_id', $company->id)
                ->with('employees')
                ->findOrFail($softwareId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $employees = $software->employees()->paginate(10);
        $employeeCollection = AdminSoftwareViewHelper::seats($employees, $company);
        $software = AdminSoftwareViewHelper::show($software);

        return Inertia::render('Adminland/Software/Show', [
            'software' => $software,
            'employees' => $employeeCollection,
            'paginator' => PaginatorHelper::getData($employees),
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
}