<?php

namespace App\Http\Controllers\Company\Recruiting;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\JobOpening\CreateJobOpening;
use App\Services\Company\Adminland\JobOpening\ToggleJobOpening;
use App\Services\Company\Adminland\JobOpening\UpdateJobOpening;
use App\Services\Company\Adminland\JobOpening\DestroyJobOpening;
use App\Http\ViewHelpers\Recruiting\RecruitingJobOpeningsViewHelper;

class RecruitingJobOpeningController extends Controller
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

        $openJobOpenings = RecruitingJobOpeningsViewHelper::openJobOpenings($company);

        return Inertia::render('Recruiting/Index', [
            'notifications' => NotificationHelper::getNotifications($employee),
            'jobOpenings' => $openJobOpenings,
        ]);
    }

    /**
     * Show the list of job openings that are fulfilled.
     *
     * @return mixed
     */
    public function fulfilled()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // is this person HR?
        if ($employee->permission_level > config('officelife.permission_level.hr')) {
            return redirect('home');
        }

        $jobOpenings = RecruitingJobOpeningsViewHelper::fulfilledJobOpenings($company);

        return Inertia::render('Recruiting/Fulfilled', [
            'notifications' => NotificationHelper::getNotifications($employee),
            'jobOpenings' => $jobOpenings,
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

        $positions = RecruitingJobOpeningsViewHelper::positions($company);
        $templates = RecruitingJobOpeningsViewHelper::templates($company);
        $teams = RecruitingJobOpeningsViewHelper::teams($company);

        return Inertia::render('Recruiting/Create', [
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

        $potentialSponsors = RecruitingJobOpeningsViewHelper::potentialSponsors(
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

        $jobOpening = (new CreateJobOpening)->execute($data);

        return response()->json([
            'data' => [
                'url' => route('recruiting.openings.index', [
                    'company' => $loggedCompany,
                ]),
            ],
        ], 201);
    }

    /**
     * Show the Edit job opening view.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $jobOpeningId
     * @return mixed
     */
    public function edit(Request $request, int $companyId, int $jobOpeningId)
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
                ->with('sponsors')
                ->findOrFail($jobOpeningId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $positions = RecruitingJobOpeningsViewHelper::positions($company);
        $templates = RecruitingJobOpeningsViewHelper::templates($company);
        $teams = RecruitingJobOpeningsViewHelper::teams($company);
        $jobOpeningDetails = RecruitingJobOpeningsViewHelper::edit($company, $jobOpening);

        return Inertia::render('Recruiting/Edit', [
            'positions' => $positions,
            'teams' => $teams,
            'templates' => $templates,
            'jobOpening' => $jobOpeningDetails,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Update the job .
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $jobOpeningId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'job_opening_id' => $jobOpeningId,
            'position_id' => $request->input('position'),
            'recruiting_stage_template_id' => $request->input('recruitingStageTemplateId'),
            'sponsors' => $request->input('sponsorsId'),
            'team_id' => $request->input('teamId'),
            'title' => $request->input('title'),
            'reference_number' => $request->input('reference_number'),
            'description' => $request->input('description'),
        ];

        $jobOpening = (new UpdateJobOpening)->execute($data);

        return response()->json([
            'data' => [
                'url' => route('recruiting.openings.show', [
                    'company' => $loggedCompany,
                    'jobOpening' => $jobOpening,
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
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // is this person the sponsor of the job?
        $isSponsor = DB::table('job_opening_sponsor')->where('employee_id', $loggedEmployee->id)
            ->where('job_opening_id', $jobOpeningId)
            ->count() === 1;

        if (! $isSponsor) {
            // is this person HR?
            if ($loggedEmployee->permission_level > config('officelife.permission_level.hr')) {
                return redirect('home');
            }
        }

        try {
            $jobOpening = JobOpening::where('company_id', $loggedCompany->id)
                ->with('team')
                ->with('position')
                ->with('position.employees')
                ->with('sponsors')
                ->with('template')
                ->with('template.stages')
                ->findOrFail($jobOpeningId);
        } catch (ModelNotFoundException $e) {
            return redirect('recruiting.openings.index');
        }

        $jobOpeningDetail = RecruitingJobOpeningsViewHelper::show($loggedCompany, $jobOpening);
        $sponsors = RecruitingJobOpeningsViewHelper::sponsors($loggedCompany, $jobOpening);
        $stats = RecruitingJobOpeningsViewHelper::stats($loggedCompany, $jobOpening);
        $candidates = RecruitingJobOpeningsViewHelper::toSort($loggedCompany, $jobOpening);

        return Inertia::render('Recruiting/Show', [
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
            'jobOpening' => $jobOpeningDetail,
            'sponsors' => $sponsors,
            'stats' => $stats,
            'candidates' => $candidates,
            'tab' => 'to_sort',
        ]);
    }

    /**
     * Show the rejected candidates of a job opening.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @return mixed
     */
    public function showRejected(Request $request, int $companyId, int $jobOpeningId): mixed
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // is this person the sponsor of the job?
        $isSponsor = DB::table('job_opening_sponsor')->where('employee_id', $loggedEmployee->id)
            ->where('job_opening_id', $jobOpeningId)
            ->count() === 1;

        if (! $isSponsor) {
            // is this person HR?
            if ($loggedEmployee->permission_level > config('officelife.permission_level.hr')) {
                return redirect('home');
            }
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
            return redirect('recruiting.openings.index');
        }

        $jobOpeningDetail = RecruitingJobOpeningsViewHelper::show($company, $jobOpening);
        $sponsors = RecruitingJobOpeningsViewHelper::sponsors($company, $jobOpening);
        $stats = RecruitingJobOpeningsViewHelper::stats($company, $jobOpening);
        $candidates = RecruitingJobOpeningsViewHelper::rejected($company, $jobOpening);

        return Inertia::render('Recruiting/Show', [
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
            'jobOpening' => $jobOpeningDetail,
            'sponsors' => $sponsors,
            'stats' => $stats,
            'candidates' => $candidates,
            'tab' => 'rejected',
        ]);
    }

    /**
     * Show the selected candidates of a job opening.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @return mixed
     */
    public function showSelected(Request $request, int $companyId, int $jobOpeningId): mixed
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // is this person the sponsor of the job?
        $isSponsor = DB::table('job_opening_sponsor')->where('employee_id', $loggedEmployee->id)
            ->where('job_opening_id', $jobOpeningId)
            ->count() === 1;

        if (! $isSponsor) {
            // is this person HR?
            if ($loggedEmployee->permission_level > config('officelife.permission_level.hr')) {
                return redirect('home');
            }
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
            return redirect('recruiting.openings.index');
        }

        $jobOpeningDetail = RecruitingJobOpeningsViewHelper::show($company, $jobOpening);
        $sponsors = RecruitingJobOpeningsViewHelper::sponsors($company, $jobOpening);
        $stats = RecruitingJobOpeningsViewHelper::stats($company, $jobOpening);
        $candidates = RecruitingJobOpeningsViewHelper::selected($company, $jobOpening);

        return Inertia::render('Recruiting/Show', [
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
            'jobOpening' => $jobOpeningDetail,
            'sponsors' => $sponsors,
            'stats' => $stats,
            'candidates' => $candidates,
            'tab' => 'selected',
        ]);
    }

    /**
     * Toggle the job status (active/inactive).
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @return JsonResponse
     */
    public function toggle(Request $request, int $companyId, int $jobOpeningId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'job_opening_id' => $jobOpeningId,
        ];

        (new ToggleJobOpening)->execute($data);

        return response()->json([
            'data' => true,
        ], 201);
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
                'url' => route('recruiting.openings.index', [
                    'company' => $loggedCompany,
                ]),
            ],
        ], 201);
    }
}
