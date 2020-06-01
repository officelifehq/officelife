<?php

namespace App\Http\Controllers\Company\Dashboard;

use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Jobs\UpdateDashboardPreference;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    /**
     * Redirect the user to the right tab.
     *
     * @return RedirectResponse
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        switch (Auth::user()->default_dashboard_view) {
            case 'company':
                $this->updateDashboard($employee, 'me');
                return Redirect::route('dashboard.company', ['company' => $company->id]);
                break;

            case 'team':
                $this->updateDashboard($employee, 'team');
                return Redirect::route('dashboard.team', ['company' => $company->id]);
                break;

            case 'hr':
                $this->updateDashboard($employee, 'hr');
                return Redirect::route('dashboard.hr', ['company' => $company->id]);
                break;

            default:
                $this->updateDashboard($employee, 'company');
                return Redirect::route('dashboard.me', ['company' => $company->id]);
                break;
        }
    }

    /**
     * Update the dashboard default view for the given employee.
     *
     * @param Employee $employee
     * @param string $view
     */
    private function updateDashboard(Employee $employee, string $view): void
    {
        UpdateDashboardPreference::dispatch([
            'employee_id' => $employee->id,
            'company_id' => $employee->company->id,
            'view' => $view,
        ])->onQueue('low');
    }
}
