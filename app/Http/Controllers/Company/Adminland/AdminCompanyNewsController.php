<?php

namespace App\Http\Controllers\Company\Adminland;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Collections\CompanyNewsCollection;
use App\Http\Controllers\Controller;
use App\Models\Company\CompanyNews;
use App\Services\Company\Adminland\CompanyNews\CreateCompanyNews;
use App\Services\Company\Adminland\CompanyNews\DestroyCompanyNews;
use App\Services\Company\Adminland\CompanyNews\UpdateCompanyNews;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminCompanyNewsController extends Controller
{
    /**
     * Show the list of company news.
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $news = $company->news()->with('author')->orderBy('created_at', 'desc')->get();

        $newsCollection = CompanyNewsCollection::prepare($news);

        return Inertia::render('Adminland/CompanyNews/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'news' => $newsCollection,
        ]);
    }

    /**
     * Show the Create news view.
     */
    public function create(): Response
    {
        return Inertia::render('Adminland/CompanyNews/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Create the company news.
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ];

        $news = (new CreateCompanyNews)->execute($data);

        return response()->json([
            'data' => $news->toObject(),
        ], 201);
    }

    /**
     * Show the company news edit page.
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function edit(Request $request, int $companyId, int $newsId)
    {
        try {
            $news = CompanyNews::where('company_id', $companyId)
                ->findOrFail($newsId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Adminland/CompanyNews/Edit', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'news' => $news->toObject(),
        ]);
    }

    /**
     * Update the company news.
     */
    public function update(Request $request, int $companyId, int $newsId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'company_news_id' => $newsId,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ];

        $news = (new UpdateCompanyNews)->execute($data);

        return response()->json([
            'data' => $news->toObject(),
        ], 200);
    }

    /**
     * Delete the company news.
     */
    public function destroy(Request $request, int $companyId, int $companyNewsId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'company_news_id' => $companyNewsId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyCompanyNews)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
