<?php

namespace App\Http\Controllers\Company\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class AuditController extends Controller
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

            $logsCollection->push([
                'name' => $log->author,
                'sentence' => $sentence,
                'date' => $log->date,
            ]);
        }

        return View::component('ShowAccountAudit', [
            'company' => $company,
            'logs' => $logsCollection,
            'user' => auth()->user()->isPartOfCompany($company),
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
