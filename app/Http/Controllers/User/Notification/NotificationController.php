<?php

namespace App\Http\Controllers\User\Notification;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Display the list of notifications.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
     */
    public function index(Request $request, int $companyId): Response
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $notifs = $loggedEmployee->notifications()
            ->orderBy('created_at', 'desc')
            ->get();

        $notificationCollection = collect([]);
        foreach ($notifs as $notification) {
            $notificationCollection->push([
                'id' => $notification->id,
                'action' => $notification->action,
                'localized_content' => $notification->content,
                'read' => $notification->read,
                'created_at' => DateHelper::formatShortDateWithTime($notification->created_at, $loggedEmployee->timezone),
            ]);
        }

        return Inertia::render('Notification/Index', [
            'allNotifications' => $notificationCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
