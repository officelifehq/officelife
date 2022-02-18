<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Company\Flow;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;

class AdminFlowCreationController extends Controller
{
    /**
     * Creation of a flow based on the hiring date of an employee.
     *
     * @return Response
     */
    public function onboarding(): Response
    {
        return Inertia::render('Adminland/Flow/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'type' => 'onboarding',
            'allowSteps' => true,
        ]);
    }
}
