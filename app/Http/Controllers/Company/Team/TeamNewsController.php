<?php

namespace App\Http\Controllers\Company\Team;

use App\Helpers\InstanceHelper;
use App\Helpers\PaginatorHelper;
use App\Http\Collections\TeamNewsCollection;
use App\Http\Controllers\Controller;
use App\Models\Company\Team;
use App\Models\Company\TeamNews;
use App\Services\Company\Team\TeamNews\CreateTeamNews;
use App\Services\Company\Team\TeamNews\DestroyTeamNews;
use App\Services\Company\Team\TeamNews\UpdateTeamNews;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeamNewsController extends Controller
{
    /**
     * Show the Team News page.
     *
     * @return mixed
     */
    public function index(Request $request, int $companyId, int $teamId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $team = Team::where('company_id', $company->id)
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // news
        $news = $team->news()->orderBy('created_at', 'desc')->paginate(3);
        $newsCollection = TeamNewsCollection::prepare($news);

        return Inertia::render('Team/TeamNews/Index', [
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
            ],
            'news' => $newsCollection,
            'paginator' => PaginatorHelper::getData($news),
        ]);
    }

    /**
     * Show the Post team news form.
     *
     * @return mixed
     */
    public function create(Request $request, int $companyId, int $teamId)
    {
        try {
            $team = Team::where('company_id', $companyId)
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Team/TeamNews/Create', [
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
            ],
        ]);
    }

    /**
     * Show the Post team news form.
     */
    public function store(Request $request, int $companyId, int $teamId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ];

        $news = (new CreateTeamNews)->execute($data);

        return response()->json([
            'data' => $news->toObject(),
        ]);
    }

    /**
     * Show the Edit team news form.
     */
    public function edit(Request $request, int $companyId, int $teamId, int $newsId): Response
    {
        $team = Team::findOrFail($teamId);
        $news = TeamNews::where('team_id', $teamId)->findOrFail($newsId);

        return Inertia::render('Team/TeamNews/Edit', [
            'team' => [
                'id' => $team->id,
                'name' => $team->name,
            ],
            'news' => $news->toObject(),
        ]);
    }

    /**
     * Update the company news.
     */
    public function update(Request $request, int $companyId, int $teamId, int $newsId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_news_id' => $newsId,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ];

        $news = (new UpdateTeamNews)->execute($data);

        return response()->json([
            'data' => $news,
        ]);
    }

    /**
     * Delete the team news.
     */
    public function destroy(Request $request, int $companyId, int $teamId, int $newsId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'team_news_id' => $newsId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyTeamNews)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
