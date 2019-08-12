<?php

namespace App\Http\Controllers\Company\Dashboard;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Redirect the user to the right tab.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        switch (auth()->user()->default_dashboard_view) {
            case 'company':
                return redirect(tenant('/dashboard/company'));
                break;

            case 'team':
                return redirect(tenant('/dashboard/team'));
                break;

            case 'hr':
                return redirect(tenant('/dashboard/hr'));
                break;

            default:
                return redirect(tenant('/dashboard/me'));
                break;
        }
    }
}
