<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Redirect the user to the right tab.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = Cache::get('currentCompany');

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
