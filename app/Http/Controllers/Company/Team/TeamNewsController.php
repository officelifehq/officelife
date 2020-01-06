<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Inertia;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\TeamNews;
use App\Http\Controllers\Controller;
use App\Services\Company\Team\TeamNews\CreateTeamNews;
use App\Services\Company\Team\TeamNews\UpdateTeamNews;
use App\Services\Company\Team\TeamNews\DestroyTeamNews;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Company\Team\Team as TeamResource;
use App\Http\Resources\Company\TeamNews\TeamNews as TeamNewsResource;

class TeamNewsController extends Controller
{
    /**
     * Show the Team News page.
     *
     * @param int $companyId
     * @param int $teamId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $companyId, $teamId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $team = Team::where('company_id', $companyId)
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $news = $team->news()->orderBy('created_at', 'desc')->paginate(3);
        $news = TeamNewsResource::collection($news);

        return Inertia::render('Team/TeamNews/Index', [
            'team' => new TeamResource($team),
            'news' => $news,
            'paginator' => [
                'count' => $news->count(),
                'currentPage' => $news->currentPage(),
                'firstItem' => $news->firstItem(),
                'hasMorePages' => $news->hasMorePages(),
                'lastItem' => $news->lastItem(),
                'lastPage' => $news->lastPage(),
                'nextPageUrl' => $news->nextPageUrl(),
                'onFirstPage' => $news->onFirstPage(),
                'perPage' => $news->perPage(),
                'previousPageUrl' => $news->previousPageUrl(),
                'total' => $news->total(),
            ],
        ]);
    }

    /**
     * Show the Post team news form.
     *
     * @param int $companyId
     * @param int $teamId
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $companyId, $teamId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $team = Team::where('company_id', $companyId)
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Team/TeamNews/Create', [
            'team' => new TeamResource($team),
        ]);
    }

    /**
     * Show the Post team news form.
     *
     * @param int $companyId
     * @param int $teamId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $companyId, $teamId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
        ];

        $news = (new CreateTeamNews)->execute($request);

        return response()->json([
            'data' => $news,
        ]);
    }

    /**
     * Show the Edit team news form.
     *
     * @param int $companyId
     * @param int $teamId
     * @param int $newsId
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $companyId, $teamId, $newsId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $team = Team::findOrFail($teamId);
        $news = TeamNews::where('team_id', $teamId)->findOrFail($newsId);

        return Inertia::render('Team/TeamNews/Edit', [
            'team' => new TeamResource($team),
            'news' => new TeamNewsResource($news),
        ]);
    }

    /**
     * Update the company news.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $newsId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $companyId, $teamId, $newsId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_news_id' => $newsId,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
        ];

        $news = (new UpdateTeamNews)->execute($request);

        return response()->json([
            'data' => $news,
        ]);
    }

    /**
     * Delete the team news.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $newsId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $companyId, $teamId, $newsId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'team_news_id' => $newsId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyTeamNews)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
