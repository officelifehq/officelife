<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Hardware;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Hardware\LendHardware;
use App\Http\ViewHelpers\Adminland\AdminHardwareViewHelper;
use App\Services\Company\Adminland\Hardware\CreateHardware;
use App\Services\Company\Adminland\Hardware\RegainHardware;
use App\Services\Company\Adminland\Hardware\UpdateHardware;
use App\Services\Company\Adminland\Hardware\DestroyHardware;

class AdminHardwareController extends Controller
{
    /**
     * Show the list of hardware.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        $hardware = $company->hardware()->with('employee')->orderBy('created_at', 'desc')->get();
        $hardwareInformation = AdminHardwareViewHelper::hardware($hardware);

        return Inertia::render('Adminland/Hardware/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'hardware' => $hardwareInformation,
            'state' => 'all',
        ]);
    }

    /**
     * Show the Create hardware view.
     *
     * @return Response
     */
    public function create(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employees = AdminHardwareViewHelper::employeesList($company);

        return Inertia::render('Adminland/Hardware/Create', [
            'employees' => $employees,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Create the hardware.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
            'serial_number' => $request->input('serial'),
        ];

        $hardware = (new CreateHardware)->execute($data);

        if ($request->input('lend_hardware')) {
            (new LendHardware)->execute([
                'company_id' => $company->id,
                'author_id' => $loggedEmployee->id,
                'employee_id' => $request->input('employee_id'),
                'hardware_id' => $hardware->id,
            ]);
        }

        return response()->json([
            'data' => $company->id,
        ], 201);
    }

    /**
     * Show the hardware.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $hardwareId
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $hardwareId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $hardware = Hardware::where('company_id', $company->id)
                ->with('employee')
                ->findOrFail($hardwareId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $information = [
            'id' => $hardware->id,
            'name' => $hardware->name,
            'serial_number' => $hardware->serial_number,
            'employee' => $hardware->employee ? [
                'id' => $hardware->employee->id,
                'name' => $hardware->employee->name,
                'avatar' => ImageHelper::getAvatar($hardware->employee, 22),
            ] : null,
        ];

        $history = AdminHardwareViewHelper::history($hardware, $employee);

        return Inertia::render('Adminland/Hardware/Show', [
            'hardware' => $information,
            'history' => $history,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Edit the hardware.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $hardwareId
     * @return mixed
     */
    public function edit(Request $request, int $companyId, int $hardwareId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $hardware = Hardware::where('company_id', $company->id)
                ->with('employee')
                ->findOrFail($hardwareId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $employees = AdminHardwareViewHelper::employeesList($company);

        return Inertia::render('Adminland/Hardware/Edit', [
            'employees' => $employees,
            'hardware' => [
                'id' => $hardware->id,
                'name' => $hardware->name,
                'serial_number' => $hardware->serial_number,
                'employee' => $hardware->employee ? [
                    'label' => $hardware->employee->name,
                    'value' => $hardware->employee->id,
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
     * @param int $hardwareId
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $hardwareId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $hardware = Hardware::findOrFail($hardwareId);

        if ($hardware->name != $request->input('name') || $hardware->serial_number != $request->input('serial')) {
            $data = [
                'company_id' => $loggedCompany->id,
                'author_id' => $loggedEmployee->id,
                'hardware_id' => $hardwareId,
                'name' => $request->input('name'),
                'serial_number' => $request->input('serial'),
            ];

            (new UpdateHardware)->execute($data);
        }

        // if the checkbox is checked and the hardware wasn't assigned to an employee
        if ($request->input('lend_hardware') && ! $hardware->employee) {
            (new LendHardware)->execute([
                'company_id' => $loggedCompany->id,
                'author_id' => $loggedEmployee->id,
                'employee_id' => $request->input('employee_id'),
                'hardware_id' => $hardware->id,
            ]);
        }

        // if the hardware was assigned to an employee
        if ($hardware->employee) {
            if ($request->input('lend_hardware')) {
                (new LendHardware)->execute([
                    'company_id' => $loggedCompany->id,
                    'author_id' => $loggedEmployee->id,
                    'employee_id' => $request->input('employee_id'),
                    'hardware_id' => $hardware->id,
                ]);
            }

            if (! $request->input('lend_hardware')) {
                (new RegainHardware)->execute([
                    'company_id' => $loggedCompany->id,
                    'author_id' => $loggedEmployee->id,
                    'hardware_id' => $hardware->id,
                ]);
            }
        }

        return response()->json([
            'data' => $hardware->id,
        ], 200);
    }

    /**
     * Delete the hardware.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $hardwareId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $hardwareId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'hardware_id' => $hardwareId,
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
        $hardware = $company->hardware()->with('employee')->orderBy('created_at', 'desc')->get();
        $hardwareInformation = AdminHardwareViewHelper::availableHardware($hardware);

        return Inertia::render('Adminland/Hardware/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'hardware' => $hardwareInformation,
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
        $hardware = $company->hardware()->with('employee')->orderBy('created_at', 'desc')->get();
        $hardwareInformation = AdminHardwareViewHelper::lentHardware($hardware);

        return Inertia::render('Adminland/Hardware/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'hardware' => $hardwareInformation,
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

        $hardwareCollection = AdminHardwareViewHelper::hardware($potentialHardware);

        if (count($hardwareCollection) == 0) {
            throw new ModelNotFoundException();
        }

        return response()->json([
            'data' => $hardwareCollection,
        ], 201);
    }
}
