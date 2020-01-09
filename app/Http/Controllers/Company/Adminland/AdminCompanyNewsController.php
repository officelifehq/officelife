<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Models\Company\CompanyNews;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\CompanyNews\CreateCompanyNews;
use App\Services\Company\Adminland\CompanyNews\UpdateCompanyNews;
use App\Services\Company\Adminland\CompanyNews\DestroyCompanyNews;
use App\Http\Resources\Company\CompanyNews\CompanyNews as CompanyNewsResource;

class AdminCompanyNewsController extends Controller
{
    /**
     * Show the list of company news.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $news = CompanyNewsResource::collection(
            $company->news()->orderBy('created_at', 'desc')->get()
        );

        return Inertia::render('Adminland/CompanyNews/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'news' => $news,
        ]);
    }

    /**
     * Show the Create news view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Adminland/CompanyNews/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Create the company news.
     *
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $companyId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
        ];

        $news = (new CreateCompanyNews)->execute($request);

        return response()->json([
            'data' => $news,
        ]);
    }

    /**
     * Show the company news edit page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $newsId
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $companyId, int $newsId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $news = CompanyNews::where('company_id', $companyId)
                ->findOrFail($newsId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Adminland/CompanyNews/Edit', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'news' => $news,
        ]);
    }

    /**
     * Update the company news.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $newsId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $companyId, $newsId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'company_news_id' => $newsId,
            'title' => $request->get('title'),
            'content' => $request->get('content'),
        ];

        $news = (new UpdateCompanyNews)->execute($request);

        return new CompanyNewsResource($news);
    }

    /**
     * Delete the company news.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $companyNewsId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $companyId, $companyNewsId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'company_news_id' => $companyNewsId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyCompanyNews)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
