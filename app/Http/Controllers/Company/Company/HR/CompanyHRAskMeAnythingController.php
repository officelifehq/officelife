<?php

namespace App\Http\Controllers\Company\Company\HR;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\AskMeAnythingSession;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\HR\CompanyHRAskMeAnythingViewHelper;
use App\Services\Company\Adminland\AskMeAnything\CreateAskMeAnythingSession;

class CompanyHRAskMeAnythingController extends Controller
{
    /**
     * Show the list of Ask Me Anything sessions.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $positionId
     * @return mixed
     */
    public function index(Request $request, int $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = CompanyHRAskMeAnythingViewHelper::index($company, $employee);

        return Inertia::render('Company/HR/AskMeAnything/Index', [
            'data' => $data,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Show the create Ask Me Anything session screen.
     *
     * @param Request $request
     * @param int $companyId
     * @return mixed
     */
    public function create(Request $request, int $companyId)
    {
        $employee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = CompanyHRAskMeAnythingViewHelper::new($company);

        return Inertia::render('Company/HR/AskMeAnything/Create', [
            'data' => $data,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Create the session.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $date = Carbon::createFromDate(
            $request->input('year'),
            $request->input('month'),
            $request->input('day')
        )->format('Y-m-d');

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'theme' => $request->input('theme'),
            'date' => $date,
        ];

        $session = (new CreateAskMeAnythingSession)->execute($data);

        return response()->json([
            'data' => route('hr.ama.show', [
                'company' => $company->id,
                'session' => $session->id,
            ]),
        ], 201);
    }

    /**
     * Show the Ask Me Anything session.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $sessionId
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $sessionId)
    {
        $employee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        try {
            $session = AskMeAnythingSession::where('company_id', $company->id)
                ->findOrFail($sessionId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $data = CompanyHRAskMeAnythingViewHelper::show($company, $session);

        return Inertia::render('Company/HR/AskMeAnything/Show', [
            'data' => $data,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }
}
