<?php

namespace App\Http\Controllers\Company\Company\KB;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Company\Page;
use App\Models\Company\Wiki;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Wiki\UpdatePage;
use App\Services\Company\Wiki\DestroyPage;
use App\Services\Company\Wiki\AddPageToWiki;
use App\Http\ViewHelpers\Company\CompanyViewHelper;
use App\Http\ViewHelpers\Company\KB\WikiViewHelper;
use App\Http\ViewHelpers\Company\KB\PageEditViewHelper;
use App\Http\ViewHelpers\Company\KB\PageShowViewHelper;
use App\Http\ViewHelpers\Company\KB\WikiShowViewHelper;
use App\Services\Company\Wiki\IncrementPageViewForPage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KnowledgeBasePageController extends Controller
{
    /**
     * Display the list of wikis.
     *
     * @param Request $request
     * @param int $companyId
     * @return Response
     */
    public function index(Request $request, int $companyId): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $statistics = CompanyViewHelper::information($company);

        return Inertia::render('Company/KB/Index', [
            'statistics' => $statistics,
            'tab' => 'kb',
            'wikis' => WikiViewHelper::index($company),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Show the Create Page page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $wikiId
     * @return mixed
     */
    public function create(Request $request, int $companyId, int $wikiId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $wiki = Wiki::where('company_id', $loggedCompany->id)
                ->findOrFail($wikiId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Company/KB/Page/Create', [
            'wiki' => WikiShowViewHelper::show($wiki, $loggedCompany),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Actually create the page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $wikiId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $wikiId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'wiki_id' => $wikiId,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ];

        $page = (new AddPageToWiki)->execute($data);

        return response()->json([
            'data' => [
                'url' => route('pages.show', [
                    'company' => $loggedCompany,
                    'wiki' => $wikiId,
                    'page' => $page,
                ]),
            ],
        ], 201);
    }

    /**
     * Display a wiki.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $wikiId
     * @param int $pageId
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $wikiId, int $pageId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $wiki = Wiki::where('company_id', $loggedCompany->id)
                ->findOrFail($wikiId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $page = Page::where('wiki_id', $wiki->id)
                ->findOrFail($pageId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        (new IncrementPageViewForPage)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'page_id' => $page->id,
        ]);

        return Inertia::render('Company/KB/Page/Show', [
            'page' => PageShowViewHelper::show($page),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Show the Edit Page page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $wikiId
     * @param int $pageId
     * @return mixed
     */
    public function edit(Request $request, int $companyId, int $wikiId, int $pageId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $wiki = Wiki::where('company_id', $loggedCompany->id)
                ->findOrFail($wikiId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $page = Page::where('wiki_id', $wiki->id)
                ->findOrFail($pageId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Company/KB/Page/Edit', [
            'page' => PageEditViewHelper::show($page),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Update the page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $wikiId
     * @param int $pageId
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $wikiId, int $pageId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'wiki_id' => $wikiId,
            'page_id' => $pageId,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ];

        (new UpdatePage)->execute($data);

        return response()->json([
            'data' => [
                'url' => route('pages.show', [
                    'company' => $loggedCompany,
                    'wiki' => $wikiId,
                    'page' => $pageId,
                ]),
            ],
        ], 200);
    }

    /**
     * Delete the page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $wikiId
     * @param int $pageId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $wikiId, int $pageId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'wiki_id' => $wikiId,
            'page_id' => $pageId,
        ];

        (new DestroyPage)->execute($data);

        return response()->json([
            'data' => [
                'url' => route('wikis.show', [
                    'company' => $loggedCompany,
                    'wiki' => $wikiId,
                ]),
            ],
        ], 200);
    }
}
