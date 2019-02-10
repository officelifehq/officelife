<?php

namespace App\Http\Controllers\Company\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $logs = $company->logs()->get();

        return view('company.account.audit.index')
            ->withLogs($logs);
    }
}
