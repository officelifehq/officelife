<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Hardware;
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
     * @return \Inertia\Response
     */
    public function index()
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
     * @return \Inertia\Response
     */
    public function create()
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
     * @return Response
     */
    public function store(Request $request, int $companyId)
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
     * @return Response
     */
    public function show(Request $request, int $companyId, int $hardwareId)
    {
        $company = InstanceHelper::getLoggedCompany();

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
                'avatar' => $hardware->employee->avatar,
            ] : null,
        ];

        $history = AdminHardwareViewHelper::history($hardware);

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
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, int $companyId, int $hardwareId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'hardware_id' => $hardwareId,
            'name' => $request->input('name'),
            'serial_number' => $request->input('serial'),
        ];

        $hardware = (new UpdateHardware)->execute($data);

        if ($request->input('lend_hardware') && $request->input('employee_id') != $hardware->employee_id) {
            (new LendHardware)->execute([
                'company_id' => $loggedCompany->id,
                'author_id' => $loggedEmployee->id,
                'employee_id' => $request->input('employee_id'),
                'hardware_id' => $hardware->id,
            ]);
        } else {
            (new RegainHardware)->execute([
                'company_id' => $loggedCompany->id,
                'author_id' => $loggedEmployee->id,
                'hardware_id' => $hardware->id,
            ]);
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
     * @return Response
     */
    public function destroy(Request $request, int $companyId, int $hardware)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'hardware_id' => $hardware,
        ];

        (new DestroyHardware)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Display the list of available hardware.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
     */
    public function available(Request $request, int $companyId)
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
     *
     * @return Response
     */
    public function lent(Request $request, int $companyId)
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
     * @param integer $companyId
     */
    public function search(Request $request, int $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();

        $search = $request->input('searchTerm');
        $potentialHardware = Hardware::where('serial_number', 'LIKE', '%'.$search.'%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
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
