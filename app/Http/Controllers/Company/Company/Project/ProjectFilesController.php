<?php

namespace App\Http\Controllers\Company\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DateHelper;
use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\AddFileToProject;
use App\Services\Company\Adminland\File\UploadFile;
use App\Services\Company\Project\DestroyProjectFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;
use App\Http\ViewHelpers\Company\Project\ProjectFilesViewHelper;

class ProjectFilesController extends Controller
{
    /**
     * Display the list of files in the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function index(Request $request, int $companyId, int $projectId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        return Inertia::render('Company/Project/Files/Index', [
            'tab' => 'files',
            'project' => ProjectViewHelper::info($project),
            'files' => ProjectFilesViewHelper::index($project, $loggedEmployee),
            'uploadcarePublicKey' => config('officelife.uploadcare_public_key'),
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }

    /**
     * Store a file.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse|null
     */
    public function store(Request $request, int $companyId, int $projectId): ?JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $project = Project::where('company_id', $loggedCompany->id)
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return null;
        }

        $file = (new UploadFile)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'uuid' => $request->input('uuid'),
            'name' => $request->input('name'),
            'original_url' => $request->input('original_url'),
            'cdn_url' => $request->input('cdn_url'),
            'mime_type' => $request->input('mime_type'),
            'size' => $request->input('size'),
            'type' => 'project_file',
        ]);

        (new AddFileToProject)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $project->id,
            'file_id' => $file->id,
        ]);

        return response()->json([
            'data' => [
                'id' => $file->id,
                'size' => FileHelper::getSize($file->size),
                'filename' => $file->name,
                'download_url' => $file->cdn_url,
                'uploader' => [
                    'id' => $loggedEmployee->id,
                    'name' => $loggedEmployee->name,
                    'avatar' => ImageHelper::getAvatar($loggedEmployee, 24),
                    'url' => route('employees.show', [
                        'company' => $loggedCompany,
                        'employee' => $loggedEmployee,
                    ]),
                ],
                'created_at' => DateHelper::formatDate($file->created_at, $loggedEmployee->timezone),
            ],
        ], 200);
    }

    /**
     * Destroy a file.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $fileId
     *
     * @return JsonResponse|null
     */
    public function destroy(Request $request, int $companyId, int $projectId, int $fileId): ?JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $project = Project::where('company_id', $loggedCompany->id)
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return null;
        }

        (new DestroyProjectFile)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $project->id,
            'file_id' => $fileId,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
