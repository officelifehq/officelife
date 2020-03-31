<?php

namespace App\Http\Controllers\Company\Dashboard;

use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Jobs\UpdateDashboardPreference;
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
                $this->updateDashboard($company, 'me');
                return Redirect::route('dashboard.company', ['company' => $company->id]);
                break;

            case 'team':
                $this->updateDashboard($company, 'team');
                return Redirect::route('dashboard.team', ['company' => $company->id]);
                break;

            case 'hr':
                $this->updateDashboard($company, 'hr');
                return Redirect::route('dashboard.hr', ['company' => $company->id]);
                break;

            default:
                $this->updateDashboard($company, 'company');
                return Redirect::route('dashboard.me', ['company' => $company->id]);
                break;
        }
    }

    private function updateDashboard(Company $company, string $view): void
    {
        UpdateDashboardPreference::dispatch([
            'employee_id' => Auth::user()->id,
            'company_id' => $company->id,
            'view' => $view,
        ])->onQueue('low');
    }
}
