<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Show the audit log.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $logs = $this->company->logs()->get();

        return view('company.audit.index')
            ->withLogs($logs);
    }
}
