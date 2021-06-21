<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Collections\TeamNewsCollection;
use App\Http\ViewHelpers\Team\TeamShowViewHelper;
use App\Http\Collections\TeamUsefulLinkCollection;
use App\Http\ViewHelpers\Team\TeamIndexViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TeamController extends Controller
{
    /**
     * Display the list of teams.
     *
     * @param Request $request
     * @param int $companyId
     * @return Response
     */
    public function index(Request $request, int $companyId): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Team/Index', [
            'teams' => TeamIndexViewHelper::index($company),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the detail of a team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function show(Request $request, int $companyId, int $teamId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $team = Team::where('company_id', $loggedCompany->id)
                ->with('leader')
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // employees
        $teamMembers = TeamShowViewHelper::employees($team);

        // recent ships
        $recentShipsCollection = TeamShowViewHelper::recentShips($team);

        // news
        $news = $team->news()->with('author')->orderBy('created_at', 'desc')->take(3)->get();
        $newsCollection = TeamNewsCollection::prepare($news);

        // does the current logged user belongs to the team?
        // this is useful to know whether the user can do actions because he's part of the team
        $belongsToTheTeam = $teamMembers->filter(function ($employee) use ($loggedEmployee) {
            return $employee['id'] === $loggedEmployee->id;
        });

        // most recent member
        $mostRecentMember = trans_choice('team.most_recent_team_member', $teamMembers->count(), [
            'link' => ($teamMembers->count() > 0) ? $teamMembers->take(1)->first()['name'] : '',
        ]);

        // birthdays
        $birthdays = TeamShowViewHelper::birthdays($team, $loggedCompany);

        // morale
        $morale = TeamShowViewHelper::morale($team, $loggedEmployee);

        // upcoming hiring date anniversaries
        $newHiresNextWeek = TeamShowViewHelper::newHiresNextWeek($team, $loggedCompany);

        return Inertia::render('Team/Show', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'team' => TeamShowViewHelper::team($team),
            'news' => $newsCollection,
            'newsCount' => $news->count(),
            'mostRecentEmployee' => $mostRecentMember,
            'employeeCount' => $teamMembers->count(),
            'employees' => $teamMembers,
            'birthdays' => $birthdays,
            'userBelongsToTheTeam' => $belongsToTheTeam->count() > 0,
            'links' => TeamUsefulLinkCollection::prepare($team->links),
            'recentShips' => $recentShipsCollection,
            'morale' => $morale,
            'newHiresNextWeek' => $newHiresNextWeek,
        ]);
    }
}
