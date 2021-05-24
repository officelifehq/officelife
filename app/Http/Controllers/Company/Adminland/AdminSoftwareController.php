<?php

namespace App\Http\Controllers\Company\Adminland;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\PaginatorHelper;
use App\Models\Company\Hardware;
use App\Models\Company\Software;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Dashboard\DashboardMeViewHelper;
use App\Services\Company\Adminland\Hardware\LendHardware;
use App\Http\ViewHelpers\Adminland\AdminHardwareViewHelper;
use App\Http\ViewHelpers\Adminland\AdminSoftwareViewHelper;
use App\Services\Company\Adminland\Hardware\RegainHardware;
use App\Services\Company\Adminland\Hardware\UpdateHardware;
use App\Services\Company\Adminland\Software\CreateSoftware;
use App\Services\Company\Adminland\Hardware\DestroyHardware;

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
     * Edit the hardware.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $softwareId
     * @return mixed
     */
    public function edit(Request $request, int $companyId, int $softwareId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $software = Hardware::where('company_id', $company->id)
                ->with('employee')
                ->findOrFail($softwareId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $employees = AdminHardwareViewHelper::employeesList($company);

        return Inertia::render('Adminland/Hardware/Edit', [
            'employees' => $employees,
            'hardware' => [
                'id' => $software->id,
                'name' => $software->name,
                'serial_number' => $software->serial_number,
                'employee' => $software->employee ? [
                    'label' => $software->employee->name,
                    'value' => $software->employee->id,
                ] : null,
            ],
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Update the hardware.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $softwareId
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $softwareId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $software = Hardware::findOrFail($softwareId);

        if ($software->name != $request->input('name') || $software->serial_number != $request->input('serial')) {
            $data = [
                'company_id' => $loggedCompany->id,
                'author_id' => $loggedEmployee->id,
                'hardware_id' => $softwareId,
                'name' => $request->input('name'),
                'serial_number' => $request->input('serial'),
            ];

            (new UpdateHardware)->execute($data);
        }

        // if the checkbox is checked and the hardware wasn't assigned to an employee
        if ($request->input('lend_hardware') && ! $software->employee) {
            (new LendHardware)->execute([
                'company_id' => $loggedCompany->id,
                'author_id' => $loggedEmployee->id,
                'employee_id' => $request->input('employee_id'),
                'hardware_id' => $software->id,
            ]);
        }

        // if the hardware was assigned to an employee
        if ($software->employee) {
            if ($request->input('lend_hardware')) {
                (new LendHardware)->execute([
                    'company_id' => $loggedCompany->id,
                    'author_id' => $loggedEmployee->id,
                    'employee_id' => $request->input('employee_id'),
                    'hardware_id' => $software->id,
                ]);
            }

            if (! $request->input('lend_hardware')) {
                (new RegainHardware)->execute([
                    'company_id' => $loggedCompany->id,
                    'author_id' => $loggedEmployee->id,
                    'hardware_id' => $software->id,
                ]);
            }
        }

        return response()->json([
            'data' => $software->id,
        ], 200);
    }

    /**
     * Delete the hardware.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $softwareId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $softwareId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'hardware_id' => $softwareId,
        ];

        (new DestroyHardware)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Display the list of available hardware.
     *
     * @param Request $request
     * @param int $companyId
     * @return Response
     */
    public function available(Request $request, int $companyId): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $software = $company->hardware()->with('employee')->orderBy('created_at', 'desc')->get();
        $softwareInformation = AdminHardwareViewHelper::availableHardware($software);

        return Inertia::render('Adminland/Hardware/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'hardware' => $softwareInformation,
            'state' => 'available',
        ]);
    }

    /**
     * Display the list of lent hardware.
     *
     * @param Request $request
     * @param int $companyId
     * @return Response
     */
    public function lent(Request $request, int $companyId): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $software = $company->hardware()->with('employee')->orderBy('created_at', 'desc')->get();
        $softwareInformation = AdminHardwareViewHelper::lentHardware($software);

        return Inertia::render('Adminland/Hardware/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'hardware' => $softwareInformation,
            'state' => 'lent',
        ]);
    }

    /**
     * Search a specific item.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();

        $search = $request->input('searchTerm');
        $potentialHardware = Hardware::where('serial_number', 'LIKE', '%'.$search.'%')
            ->orWhere('name', 'LIKE', '%'.$search.'%')
            ->with('employee')
            ->get();

        $softwareCollection = AdminHardwareViewHelper::hardware($potentialHardware);

        if (count($softwareCollection) == 0) {
            throw new ModelNotFoundException();
        }

        return response()->json([
            'data' => $softwareCollection,
        ], 201);
    }
}
