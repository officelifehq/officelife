<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\File\UploadFile;
use App\Services\Company\Adminland\Company\RenameCompany;
use App\Http\ViewHelpers\Adminland\AdminGeneralViewHelper;
use App\Services\Company\Adminland\Company\UpdateCompanyLogo;
use App\Services\Company\Adminland\Company\UpdateCompanyCurrency;
use App\Services\Company\Adminland\Company\UpdateCompanyLocation;
use App\Services\Company\Adminland\Company\UpdateCompanyFoundedDate;

class AdminGeneralController extends Controller
{
    /**
     * Show the General settings company page.
     *
     * @return Response
     */
    public function index(): Response
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $information = AdminGeneralViewHelper::information($loggedCompany, $loggedEmployee);
        $currencies = AdminGeneralViewHelper::currencies();

        return Inertia::render('Adminland/General/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'information' => $information,
            'currencies' => $currencies,
        ]);
    }

    /**
     * Rename the company.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function rename(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
        ];

        (new RenameCompany)->execute($data);

        return response()->json([
            'data' => true,
        ], 201);
    }

    /**
     * Update the company’s currency.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function currency(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'currency' => $request->input('currency'),
        ];

        (new UpdateCompanyCurrency)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Update the company’s logo.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function logo(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $file = (new UploadFile)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'uuid' => $request->input('uuid'),
            'name' => $request->input('name'),
            'original_url' => $request->input('original_url'),
            'cdn_url' => $request->input('cdn_url'),
            'mime_type' => $request->input('mime_type'),
            'size' => $request->input('size'),
            'type' => 'company_logo',
        ]);

        (new UpdateCompanyLogo)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'file_id' => $file->id,
        ]);

        return response()->json([
            'data' => ImageHelper::getImage($file, 300, 300),
        ], 200);
    }

    /**
     * Update the company’s founded date.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function date(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        (new UpdateCompanyFoundedDate)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'year' => $request->input('year'),
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Update the company’s location.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function location(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        (new UpdateCompanyLocation)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'location' => $request->input('location'),
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
