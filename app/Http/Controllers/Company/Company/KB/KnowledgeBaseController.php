<?php

namespace App\Http\Controllers\Company\Company\KB;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Company\Wiki;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Wiki\CreateWiki;
use App\Http\ViewHelpers\Company\CompanyViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\KB\KnowledgeBaseViewHelper;
use App\Http\ViewHelpers\Company\KB\KnowledgeBaseShowViewHelper;

class KnowledgeBaseController extends Controller
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
            'wikis' => KnowledgeBaseViewHelper::index($company),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Show the Create wiki page.
     *
     * @param Request $request
     * @param int $companyId
     * @return Response
     */
    public function create(Request $request, int $companyId): Response
    {
        return Inertia::render('Company/KB/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Actually create the wiki.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'title' => $request->input('title'),
        ];

        $wiki = (new CreateWiki)->execute($data);

        return response()->json([
            'data' => [
                'url' => route('wikis.show', [
                    'company' => $loggedCompany,
                    'wiki' => $wiki,
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
     * @return Response
     */
    public function show(Request $request, int $companyId, int $wikiId): Response
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $wiki = Wiki::where('company_id', $loggedCompany->id)
                ->findOrFail($wikiId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Company/KB/Show', [
            'wiki' => KnowledgeBaseShowViewHelper::show($wiki, $loggedCompany),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
