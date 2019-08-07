<?php

namespace App\Http\Controllers\Company\Adminland;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class AdminAuditController extends Controller
{
    /**
     * Show the audit log.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = Cache::get('currentCompany');
        $logs = $company->logs()->paginate(15);

        // @TODO: extract this entire listing into a dedicated class somewhere
        $logsCollection = collect([]);
        $sentence = '';
        foreach ($logs as $log) {
            if ($log->action == 'account_created') {
                $sentence = 'Created the account.';
            }

            if ($log->action == 'employee_added_to_company') {
                $sentence = 'Added '.$log->employee.' as an employee.';
            }

            if ($log->action == 'team_created') {
                $sentence = 'Created the team called '.$log->team.'.';
            }

            if ($log->action == 'employee_added_to_team') {
                $sentence = 'Added '.$log->employee.' to '.$log->team.'.';
            }

            if ($log->action == 'employee_removed_from_team') {
                $sentence = 'Removed '.$log->employee.' from '.$log->team.'.';
            }

            if ($log->action == 'employee_updated') {
                $sentence = 'Updated information about '.$log->employee.'.';
            }

            if ($log->action == 'employee_updated_hiring_information') {
                $sentence = 'Updated hiring about '.$log->employee.'.';
            }

            if ($log->action == 'manager_assigned') {
                $sentence = 'Assigned '.$log->manager.' as the manager of '.$log->employee.'.';
            }

            if ($log->action == 'manager_unassigned') {
                $sentence = 'Removed '.$log->manager.' as the manager of '.$log->employee.'.';
            }

            if ($log->action == 'employee_invited_to_become_user') {
                $sentence = 'Sent an invitation to '.$log->employee.' to join the company.';
            }

            if ($log->action == 'position_created') {
                $sentence = 'Created a position called '.$log->position.'.';
            }

            if ($log->action == 'position_updated') {
                $sentence = 'Updated the position formely called '.$log->object->{'position_old_title'}.' to '.$log->position.'.';
            }

            if ($log->action == 'position_destroyed') {
                $sentence = 'Destroyed the position called '.$log->position.'.';
            }

            if ($log->action == 'position_assigned') {
                $sentence = 'Assigned to '.$log->employee.' the position called '.$log->position.'.';
            }

            if ($log->action == 'position_removed') {
                $sentence = 'Removed the position called '.$log->position.' to '.$log->employee.' .';
            }

            if ($log->action == 'flow_created') {
                $sentence = 'Created the flow called '.$log->object->{'flow_name'}.'.';
            }

            if ($log->action == 'employee_worklog_logged') {
                $sentence = 'Added a worklog.';
            }

            if ($log->action == 'employee_status_created') {
                $sentence = 'Added an employee status called '.$log->object->{'employee_status_name'}.'.';
            }

            if ($log->action == 'employee_status_updated') {
                $sentence = 'Changed the name of the employee status from '.$log->object->{'employee_status_old_name'}.' to '.$log->object->{'employee_status_new_name'}.'.';
            }

            if ($log->action == 'employee_status_destroyed') {
                $sentence = 'Destroyed the employee status called '.$log->object->{'employee_status_name'}.'.';
            }

            if ($log->action == 'employee_status_assigned') {
                $sentence = 'Assigned the employee status called '.$log->object->{'employee_status_name'}.' to '.$log->employee.'.';
            }

            if ($log->action == 'employee_status_removed') {
                $sentence = 'Removed the employee status called '.$log->object->{'employee_status_name'}.' from '.$log->employee.'.';
            }

            $logsCollection->push([
                'name' => $log->author,
                'sentence' => $sentence,
                'date' => $log->date,
            ]);
        }

        return View::component('ShowAccountAudit', [
            'company' => $company,
            'logs' => $logsCollection,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'notifications' => auth()->user()->notifications->where('read', false)->take(5),
            'paginator' => [
                'count' => $logs->count(),
                'currentPage' => $logs->currentPage(),
                'firstItem' => $logs->firstItem(),
                'hasMorePages' => $logs->hasMorePages(),
                'lastItem' => $logs->lastItem(),
                'lastPage' => $logs->lastPage(),
                'nextPageUrl' => $logs->nextPageUrl(),
                'onFirstPage' => $logs->onFirstPage(),
                'perPage' => $logs->perPage(),
                'previousPageUrl' => $logs->previousPageUrl(),
                'total' => $logs->total(),
            ],
        ]);
    }
}
