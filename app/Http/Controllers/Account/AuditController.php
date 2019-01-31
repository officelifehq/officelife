<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class AuditController extends Controller
{
    /**
     * Show the audit log.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = auth()->user()->account->logs()->get();

        return view('account.audit.index')
            ->withLogs($logs);
    }
}
