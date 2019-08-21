<?php

namespace App\Http\Controllers\Company\Dashboard;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    /**
     * Redirect the user to the right tab.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();

        switch (Auth::user()->default_dashboard_view) {
            case 'company':
                return Redirect::route('dashboard.company', ['company' => $company->id]);
                break;

            case 'team':
                return Redirect::route('dashboard.team', ['company' => $company->id]);
                break;

            case 'hr':
                return Redirect::route('dashboard.hr', ['company' => $company->id]);
                break;

            default:
                return Redirect::route('dashboard.me', ['company' => $company->id]);
                break;
        }
    }
}
