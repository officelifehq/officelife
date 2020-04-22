<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Models\Company\CompanyNews;
use App\Http\Controllers\Controller;
use App\Http\Collections\CompanyNewsCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\CompanyNews\CreateCompanyNews;
use App\Services\Company\Adminland\CompanyNews\UpdateCompanyNews;
use App\Services\Company\Adminland\CompanyNews\DestroyCompanyNews;

class AdminCompanyNewsController extends Controller
{
    /**
     * Show the list of company news.
     *
     * @return \Inertia\Response
     */
    public function index()
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
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Adminland/CompanyNews/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Create the company news.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
     */
    public function store(Request $request, $companyId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
        ];

        $news = (new CreateCompanyNews)->execute($request);

        return response()->json([
            'data' => $news->toObject(),
        ], 201);
    }

    /**
     * Show the company news edit page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $newsId
     *
     * @return Response
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
     *
     * @param Request $request
     * @param int $companyId
     * @param int $newsId
     *
     * @return Response
     */
    public function update(Request $request, $companyId, $newsId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'company_news_id' => $newsId,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
        ];

        $news = (new UpdateCompanyNews)->execute($request);

        return response()->json([
            'data' => $news->toObject(),
        ], 200);
    }

    /**
     * Delete the company news.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $companyNewsId
     *
     * @return Response
     */
    public function destroy(Request $request, $companyId, $companyNewsId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $loggedCompany->id,
            'company_news_id' => $companyNewsId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyCompanyNews)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
