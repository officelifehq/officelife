<?php

namespace App\Http\Controllers\Company\Company\HR;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\HR\CompanyHRAskMeAnythingViewHelper;
use App\Models\Company\AskMeAnythingSession;
use App\Services\Company\Adminland\AskMeAnything\AnswerAskMeAnythingQuestion;
use App\Services\Company\Adminland\AskMeAnything\CreateAskMeAnythingSession;
use App\Services\Company\Adminland\AskMeAnything\DestroyAskMeAnythingSession;
use App\Services\Company\Adminland\AskMeAnything\ToggleAskMeAnythingSession;
use App\Services\Company\Adminland\AskMeAnything\UpdateAskMeAnythingSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyHRAskMeAnythingController extends Controller
{
    /**
     * Show the list of Ask Me Anything sessions.
     *
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
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $sessionId)
    {
        $employee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        try {
            $session = AskMeAnythingSession::where('company_id', $company->id)
                ->with('questions')
                ->findOrFail($sessionId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $data = CompanyHRAskMeAnythingViewHelper::show($company, $session, $employee, false);

        return Inertia::render('Company/HR/AskMeAnything/Show', [
            'data' => $data,
            'tab' => 'unanswered',
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Show the Edit Ask Me Anything session screen.
     *
     * @return mixed
     */
    public function edit(Request $request, int $companyId, int $sessionId)
    {
        $employee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        try {
            $session = AskMeAnythingSession::where('company_id', $company->id)
                ->findOrFail($sessionId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $data = CompanyHRAskMeAnythingViewHelper::edit($company, $session);

        return Inertia::render('Company/HR/AskMeAnything/Edit', [
            'data' => $data,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Update the session.
     */
    public function update(Request $request, int $companyId, int $sessionId): JsonResponse
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
            'ask_me_anything_session_id' => $sessionId,
            'theme' => $request->input('theme'),
            'date' => $date,
        ];

        $session = (new UpdateAskMeAnythingSession)->execute($data);

        return response()->json([
            'data' => route('hr.ama.show', [
                'company' => $company->id,
                'session' => $session->id,
            ]),
        ], 200);
    }

    /**
     * Show the Ask Me Anything session for the answered questions.
     *
     * @return mixed
     */
    public function showAnswered(Request $request, int $companyId, int $sessionId)
    {
        $employee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        try {
            $session = AskMeAnythingSession::where('company_id', $company->id)
                ->with('questions')
                ->findOrFail($sessionId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $data = CompanyHRAskMeAnythingViewHelper::show($company, $session, $employee, true);

        return Inertia::render('Company/HR/AskMeAnything/Answered', [
            'data' => $data,
            'tab' => 'answered',
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Toggle the question.
     */
    public function toggle(Request $request, int $companyId, int $sessionId, int $questionId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'ask_me_anything_session_id' => $sessionId,
            'ask_me_anything_question_id' => $questionId,
        ];

        $question = (new AnswerAskMeAnythingQuestion)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Toggle the session.
     */
    public function toggleStatus(Request $request, int $companyId, int $sessionId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'ask_me_anything_session_id' => $sessionId,
        ];

        $session = (new ToggleAskMeAnythingSession)->execute($data);

        return response()->json([
            'data' => $session->active,
        ], 200);
    }

    /**
     * Show the Delete Ask Me Anything session screen.
     *
     * @return mixed
     */
    public function delete(Request $request, int $companyId, int $sessionId)
    {
        $employee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        try {
            $session = AskMeAnythingSession::where('company_id', $company->id)
                ->findOrFail($sessionId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $data = CompanyHRAskMeAnythingViewHelper::delete($company, $session);

        return Inertia::render('Company/HR/AskMeAnything/Delete', [
            'data' => $data,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Destroy the session.
     */
    public function destroy(Request $request, int $companyId, int $sessionId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'ask_me_anything_session_id' => $sessionId,
        ];

        (new DestroyAskMeAnythingSession)->execute($data);

        return response()->json([
            'data' => route('hr.ama.index', [
                'company' => $company->id,
            ]),
        ], 200);
    }
}
