<?php

namespace App\Http\Controllers\Company\Company;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\CompanyViewHelper;
use App\Services\Company\GuessEmployeeGame\VoteGuessEmployeeGame;

class CompanyController extends Controller
{
    /**
     * All the information about the company, for public use.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $statistics = CompanyViewHelper::information($company);
        $latestQuestions = CompanyViewHelper::latestQuestions($company);
        $birthdaysThisWeek = CompanyViewHelper::birthdaysThisWeek($company);
        $newHiresThisWeek = CompanyViewHelper::newHiresThisWeek($company);
        $latestShips = CompanyViewHelper::latestShips($company);
        $latestSkills = CompanyViewHelper::latestSkills($company);
        $latestNews = CompanyViewHelper::latestNews($company);
        $guessEmployeeGameInformation = CompanyViewHelper::guessEmployeeGameInformation($employee, $company);
        $employees = CompanyViewHelper::employees($company);
        $teams = CompanyViewHelper::teams($company);
        $upcomingHiringDateAnniversaries = CompanyViewHelper::upcomingHiredDateAnniversaries($company);

        return Inertia::render('Company/Index', [
            'tab' => 'company',
            'statistics' => $statistics,
            'latestQuestions' => $latestQuestions,
            'birthdaysThisWeek' => $birthdaysThisWeek,
            'newHiresThisWeek' => $newHiresThisWeek,
            'latestShips' => $latestShips,
            'latestSkills' => $latestSkills,
            'latestNews' => $latestNews,
            'game' => $guessEmployeeGameInformation,
            'employees' => $employees,
            'teams' => $teams,
            'hiringDateAnniversaries' => $upcomingHiringDateAnniversaries,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Record the vote made on a Guess Employee Game.
     *
     * @return JsonResponse
     */
    public function vote(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $loggedEmployee->id,
            'game_id' => $request->input('gameId'),
            'choice_id' => $request->input('choiceId'),
        ];

        (new VoteGuessEmployeeGame)->execute($data);

        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Create a new Guess Employee Game entry.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return JsonResponse
     */
    public function replay(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $guessEmployeeGameInformation = CompanyViewHelper::guessEmployeeGameInformation($loggedEmployee, $loggedCompany);

        return response()->json([
            'game' => $guessEmployeeGameInformation,
        ], 200);
    }
}
