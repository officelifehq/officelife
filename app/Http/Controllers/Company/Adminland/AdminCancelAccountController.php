<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\Company\DestroyCompany;

class AdminCancelAccountController extends Controller
{
    /**
     * Show the Cancel account page.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Adminland/General/Cancel/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Destroy the company.
     *
     * @param Request $request
     * @param int $companyId
     * @return mixed
     */
    public function destroy(Request $request, int $companyId)
    {
        if (config('officelife.demo_mode')) {
            return redirect()->route('home');
        }

        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyCompany)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
