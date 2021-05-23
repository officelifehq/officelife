<?php

namespace App\Http\Controllers\Company\Employee\Performance\Surveys;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Employee\EmployeeSurveysViewHelper;

class EmployeeSurveysController extends Controller
{
    /**
     * Display all the surveys about the employee being a manager.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return Response
     */
    public function index(Request $request, int $companyId, int $employeeId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->with('rateYourManagerSurveys')
                ->with('rateYourManagerSurveys.answers')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // surveys
        $surveys = EmployeeSurveysViewHelper::rateYourManagerSurveys($employee);

        // if there are no surveys, redirect to the employee profile page
        if (! $surveys) {
            return redirect()->route('employees.show', [
                'company' => $company,
                'employee' => $employee,
            ]);
        }

        return Inertia::render('Employee/Performance/Surveys/Index', [
            'menu' => 'performance',
            'employee' => $employee->toObject(),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'surveys' => $surveys,
        ]);
    }

    /**
     * Display a specific survey about a specific month.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @param int $surveyId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
     */
    public function show(Request $request, int $companyId, int $employeeId, int $surveyId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->with('rateYourManagerSurveys')
                ->with('rateYourManagerSurveys.answers')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Employee/Performance/Surveys/Show', [
            'employee' => [
                'id' => $employeeId,
                'name' => $employee->name,
            ],
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'survey' => EmployeeSurveysViewHelper::informationAboutSurvey($surveyId),
        ]);
    }
}
