<?php

namespace App\Http\Controllers\Company\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;

class ProjectFilesController extends Controller
{
    /**
     * Display the list of files in the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function index(Request $request, int $companyId, int $projectId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $project = Project::where('company_id', $company->id)
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Company/Project/Files/Index', [
            'tab' => 'files',
            'project' => ProjectViewHelper::info($project),
            'uploadcarePublicKey' => config('officelife.uploadcare_public_key'),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
