<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Models\Company\JobOpening;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\JobOpening\CreateJobOpening;
use App\Services\Company\Adminland\JobOpening\DestroyJobOpening;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRJobOpeningsViewHelper;

class DashboardHRJobOpeningController extends Controller
{
    /**
     * Show the list of job openings.
     *
     * @return mixed
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // is this person HR?
        if ($employee->permission_level > config('officelife.permission_level.hr')) {
            return redirect('home');
        }

        $openJobOpenings = DashboardHRJobOpeningsViewHelper::openJobOpenings($company);

        return Inertia::render('Dashboard/HR/JobOpenings/Index', [
            'notifications' => NotificationHelper::getNotifications($employee),
            'jobOpenings' => $openJobOpenings,
        ]);
    }

    /**
     * Show the Create job opening view.
     *
     * @param Request $request
     * @param int $companyId
     * @return mixed
     */
    public function create(Request $request, int $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // is this person HR?
        if ($employee->permission_level > config('officelife.permission_level.hr')) {
            return redirect('home');
        }

        $positions = DashboardHRJobOpeningsViewHelper::positions($company);
        $templates = DashboardHRJobOpeningsViewHelper::templates($company);
        $teams = DashboardHRJobOpeningsViewHelper::teams($company);

        return Inertia::render('Dashboard/HR/JobOpenings/Create', [
            'positions' => $positions,
            'teams' => $teams,
            'templates' => $templates,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Get the list of potential sponsors for this job opening.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return JsonResponse
     */
    public function sponsors(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();

        $potentialSponsors = DashboardHRJobOpeningsViewHelper::potentialSponsors(
            $company,
            $request->input('searchTerm')
        );

        return response()->json([
            'data' => $potentialSponsors,
        ]);
    }

    /**
     * Store the new job opening.
     *
     * @param Request $request
     * @param integer $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'position_id' => $request->input('position'),
            'recruiting_stage_template_id' => $request->input('recruitingStageTemplateId'),
            'sponsors' => $request->input('sponsorsId'),
            'team_id' => $request->input('teamId'),
            'title' => $request->input('title'),
            'reference_number' => $request->input('reference_number'),
            'description' => $request->input('description'),
        ];

        $company = (new CreateJobOpening)->execute($data);

        return response()->json([
            'data' => [
                'url' => route('dashboard.hr.openings.index', [
                    'company' => $company,
                ]),
            ],
        ], 201);
    }

    /**
     * Show the detail of a job opening.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $jobOpeningId): mixed
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // is this person HR?
        if ($employee->permission_level > config('officelife.permission_level.hr')) {
            return redirect('home');
        }

        try {
            $jobOpening = JobOpening::where('company_id', $company->id)
                ->with('team')
                ->with('position')
                ->with('position.employees')
                ->with('sponsors')
                ->with('template')
                ->with('template.stages')
                ->findOrFail($jobOpeningId);
        } catch (ModelNotFoundException $e) {
            return redirect('dashboard.hr.openings.index');
        }

        $jobOpeningDetail = DashboardHRJobOpeningsViewHelper::show($company, $jobOpening);
        $sponsors = DashboardHRJobOpeningsViewHelper::sponsors($company, $jobOpening);

        return Inertia::render('Dashboard/HR/JobOpenings/Show', [
            'notifications' => NotificationHelper::getNotifications($employee),
            'jobOpening' => $jobOpeningDetail,
            'sponsors' => $sponsors,
            'tab' => 'to_sort',
        ]);
    }

    /**
     * Delete the job .
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $jobOpeningId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'job_opening_id' => $jobOpeningId,
        ];

        (new DestroyJobOpening)->execute($data);

        return response()->json([
            'data' => [
                'url' => route('dashboard.hr.openings.index', [
                    'company' => $loggedCompany,
                ]),
            ],
        ], 201);
    }
}
